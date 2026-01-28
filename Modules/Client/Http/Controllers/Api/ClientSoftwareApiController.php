<?php

namespace Modules\Client\Http\Controllers\Api;

use Modules\Client\Models\Client;
use Modules\Client\Models\ClientSMSLog;
use Modules\Client\Models\ClientSoftwarePayment;
use Modules\Generic\Classes\Constants;
use Modules\Generic\Http\Controllers\Api\GenericApiController;
use Modules\Generic\Classes\SMS;
use Modules\Generic\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Modules\Generic\Http\Controllers\Front\PaymobFrontController;

class ClientSoftwareApiController extends GenericApiController
{
//    public $packages;
    public function __construct()
    {
//        $this->packages = json_decode(file_get_contents('payment_packages.json'));
    }

    public function getPayment($token){
        $packages = @Client::where('token', $token)->first()->sw_payments;
        $packages = json_decode($packages) ?? ['packages' => []];
        return response()->json($packages)->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);

    }
    public function getClientInvoices($token){
        $invoices = ClientSoftwarePayment::where('token', $token)->where('status', 'paid')->orderBy('id','desc')->limit(3)->get();
        return response()->json($invoices)->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);

    }
    public function createPayment(){
        $client_token = request('ct');
        $client_package = request('p');

        $client = Client::where('token', $client_token)->first();
        if(!$client) {
            return Redirect::to(env('APP_URL'))->with('error', 'Invalid client token');
        }

        $packages = json_decode($client->sw_payments) ?? (object)['packages' => []];
        $package = @$packages->packages[($client_package - 1)];

        if(!$package) {
            return Redirect::to(env('APP_URL'))->with('error', 'Invalid package');
        }
        // Create invoice with pending status
        $invoice = ClientSoftwarePayment::create([
            'token' => $client->token,
            'title' => $package->name_en ?? 'Software Subscription',
            'price' => $package->price_usd ?? 0,
            'package_id' => $client_package,
            'date_from' => Carbon::now()->toDateString(),
            'date_to' => Carbon::now()->addDays(@(int)$package->duration)->toDateString(),
            'status' => 'pending',
            'response' => []
        ]);

        // Pass invoice_id, client_token, and package through payment gateway
        $client_package_url = $this->getPackage($client_package, $client_token, $invoice->id);

        return Redirect::to($client_package_url);

    }
    public function storePayment(){
        $request = request()->all();

        // Get merchant_order_id from callback and decode it
        $merchantOrderId = request('merchant_order_id');
        $orderData = $this->decodeMerchantOrderId($merchantOrderId);

        if(!$orderData) {
            return Redirect::to(env('APP_URL'))->with('error', 'Invalid payment data');
        }

        // Extract data from decoded merchant_order_id
        $invoice_id = $orderData['invoice_id'] ?? null;
        $client_token = $orderData['client_token'] ?? null;
        $package_id = $orderData['package_id'] ?? null;
        $sw_url = $orderData['sw_url'] ?? '';

        // Find the invoice
        $invoice = ClientSoftwarePayment::find($invoice_id);
        if(!$invoice) {
            return Redirect::to(env('APP_URL'))->with('error', 'Invoice not found');
        }
    
        // Find the client
        $client = Client::where('token', $client_token)->first();
        if(!$client) {
            return Redirect::to(env('APP_URL'))->with('error', 'Client not found');
        }
        
        $packages = json_decode($client->sw_payments) ?? (object)['packages' => []];
        $package = @$packages->packages[($package_id - 1)];

        if(!$package) {
            return Redirect::to(env('APP_URL'))->with('error', 'Invalid package');
        }

        // Update invoice response
        $invoice->response = $request;

        // Check if payment was successful
        // Payment is successful only if: success=true AND pending=false AND error_occured=false AND is_voided=false AND is_refunded=false
        $success = @$request['success'];
        $pending = @$request['pending'];
        $errorOccured = @$request['error_occured'];
        $isVoided = @$request['is_voided'];
        $isRefunded = @$request['is_refunded'];

        $is_payment_success = ($success === true || $success === 'true' || $success === 1 || $success === '1')
            && !($pending === true || $pending === 'true' || $pending === 1 || $pending === '1')
            && !($errorOccured === true || $errorOccured === 'true' || $errorOccured === 1 || $errorOccured === '1')
            && !($isVoided === true || $isVoided === 'true' || $isVoided === 1 || $isVoided === '1')
            && !($isRefunded === true || $isRefunded === 'true' || $isRefunded === 1 || $isRefunded === '1');


        if($is_payment_success) {
            // Payment successful - update invoice status and client subscription
            $invoice->status = 'paid';
            $invoice->price = request('amount_cents') / 100;
            $invoice->save();

            $client->date_to = Carbon::now()->addDays(@(int)$package->duration)->toDateString();
            $client->save();

            /* update maher system for renew clients */
            $amount_box = DB::connection('mysql_maher')->table('sw_gym_money_boxes')->latest()->first();
            $amount_after = $this->amountAfter($amount_box->amount, $amount_box->amount_before, $amount_box->operation);
            $price = request('amount_cents') / 100;
            $notes = 'تجديد: عميل مشترك "'.@$client->sms_sender_id.'" تجديد اشترك في "'.@$package->name_ar.'" في الفترة من '.Carbon::now()->toDateString().' الي '.Carbon::now()->addDays((int)@$package->duration)->toDateString().', دفع مبلغ "'.@$price.'"';

            DB::connection('mysql_maher')->table('sw_gym_money_boxes')->insert([
                'user_id' => 1,
                'amount' => @$price,
                'vat' => 0,
                'operation' => 0,
                'amount_before' => $amount_after,
                'notes' => $notes,
                'type' => 1,
                'payment_type' => 1,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ]);
            /* update maher system for renew clients */


            return Redirect::to($sw_url . '/api/sw-payment/s?p=' . $package_id . '&pd=' . ($package->duration) . '&t=true&ct=' . $client_token);
        } else {
            // Payment failed - update invoice status
            $invoice->status = 'failed';
            $invoice->save();

            return Redirect::to($sw_url . '/api/sw-payment/s?p=' . $package_id . '&pd=0&t=false&ct=' . $client_token);
        }

    }

    /**
     * Decode merchant order ID from Paymob callback
     *
     * @param string $merchantOrderId
     * @return array|null
     */
    protected function decodeMerchantOrderId($merchantOrderId)
    {
        try {
            $decoded = base64_decode($merchantOrderId);
            return json_decode($decoded, true);
        } catch (\Exception $e) {
            return null;
        }
    }
    public function amountAfter($amount, $amountBefore, $operation)
    {
        if ($operation == 0) {
            return ($amountBefore + $amount);
        } elseif ($operation == 1) {
            return ($amountBefore - $amount);
        } elseif ($operation == 2) {
            return ($amountBefore - $amount);
        }

        return $amount;
    }
    public function getPackage($id, $token, $invoice_id = null){
        $client = @Client::where('token', $token)->first();

        $package_payment = json_decode($client->sw_payments) ?? (object)['packages' => []];
        $package_payment = $package_payment->packages[$id-1];

        $paymob = new PaymobFrontController();
        $paymentData = [
            'name' => $package_payment->name_en,
            'price' => (float)$package_payment->price_usd,
            'desc' => trans('sw.payment_subscription_msg', ['name' => $package_payment->name_en]),
            'qty' => 1,
            'duration' => $package_payment->duration,
            // Add custom client data that will be passed through and received in callback
            'client_data' => [
                'gym_name' => $client->name ?? 'Gym',
                'gym_email' => $client->email ?? 'gymmawy.com@gmail.com',
                'gym_phone' => $client->phone ?? '01002509905',
                'package_id' => $id,
                'client_token' => $token,
                'invoice_id' => $invoice_id,
                'sw_url' => $client->sw_url ?? '',
                'lang' => 'en',
            ],
            // Custom callback URL for this specific controller
            // 'callback_url' => route('client.sw.payment.callback')
        ];

        $payment_url = $paymob->payment($paymentData);
        return @$payment_url;
    }
}

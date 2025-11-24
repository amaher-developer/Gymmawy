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
        $invoices = ClientSoftwarePayment::where('token', $token)->orderBy('id','desc')->limit(3)->get();
        return response()->json($invoices)->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);

    }
    public function createPayment(){
        $client_token = request('ct');
        $client_package = request('p');
        $client_package_url = $this->getPackage($client_package, $client_token);
        if($client_token && $client_package_url) {
            session(['sw_client_token' => $client_token]);
            session(['sw_client_package' => $client_package]);
        }
        return Redirect::to($client_package_url);

    }
    public function storePayment(){

        $client_token = session('sw_client_token');
        $client_package = session('sw_client_package');
//        $packages = $this->packages->packages;

        $client = Client::where('token', $client_token)->first();
        $packages = @$client->sw_payments;
        $packages = json_decode($packages) ?? ['packages' => []];

        if($client && in_array($client_package, [1,2,3,4])){
            $package = @$packages->packages[($client_package - 1)];
            if(@$package) {
                $client->date_to = Carbon::now()->addDays(@$package->duration)->toDateString();
                $client->save();

                $request = request()->all();
                $price = request('amount_cents') / 100;

                ClientSoftwarePayment::create(['token' => $client->token, 'title' => 'title', 'price' => $price, 'package_id' => $client_package, 'date_from' => Carbon::now()->toDateString(), 'date_to' => Carbon::now()->addDays(@$package->duration)->toDateString(), 'response' => ($request)]);


                /* update maher system for renew clients */
                $amount_box = DB::connection('mysql_maher')->table('sw_gym_money_boxes')->latest()->first();
                $amount_after = $this->amountAfter($amount_box->amount, $amount_box->amount_before, $amount_box->operation);
                $notes = 'تجديد: عميل مشترك "'.@$client->sms_sender_id.'" تجديد اشترك في "'.@$package->name_ar.'" في الفترة من '.Carbon::now()->toDateString().' الي '.Carbon::now()->addDays(@$package->duration)->toDateString().', دفع مبلغ "'.@(request('amount_cents') / 100).'"';

                DB::connection('mysql_maher')->table('sw_gym_money_boxes')->insert([
                    'user_id' => 1
                    , 'amount' => @$price
                    , 'vat' => 0
                    , 'operation' => 0
                    , 'amount_before' => $amount_after
                    , 'notes' => $notes
                    , 'type' => 1
                    , 'payment_type' => 1
                    ,'created_at' => Carbon::now()->toDateTimeString()
                    ,'updated_at' => Carbon::now()->toDateTimeString()
                ]);
                /* update maher system for renew clients */

                return Redirect::to($client->sw_url . '/api/sw-payment/s?p=' . $client_package . '&pd=' . ($package->duration) . '&t=' . @$request['success'] . '&ct=' . $client_token);
            }
        }
        $app_url = env('APP_URL');
        return Redirect::to($app_url);

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
    public function getPackage($id, $token){
        $package = @Client::where('token', $token)->first()->sw_payments;
        $package = json_decode($package) ?? ['packages' => []];
        $package = $package->packages[$id-1];
//        $package = $this->packages->packages[$id-1];
        if(@$package)
            return $package->url;

        return false;
    }
}

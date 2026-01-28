<?php

namespace Modules\Generic\Http\Controllers\Front;

use Modules\Generic\Models\Setting;
use Modules\Generic\Classes\Constants;
use Modules\Software\Models\GymSWClientPaymentInvoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
  
class PaymobFrontController extends GenericFrontController
{
    protected $apiKey;
    protected $integrationId;
    protected $iframeId;
    protected $hmacSecret;

    public function __construct()
    {
        parent::__construct();

        // Load Paymob credentials from environment or use hardcoded values
        $this->apiKey = env('PAYMOB_API_KEY', 'ZXlKMGVYQWlPaUpLVjFRaUxDSmhiR2NpT2lKSVV6VXhNaUo5LmV5SnVZVzFsSWpvaWFXNXBkR2xoYkNJc0ltTnNZWE56SWpvaVRXVnlZMmhoYm5RaUxDSndjbTltYVd4bFgzQnJJam96TkRNNU9EVjkuNF9ROGFDX0VKRzdCS3hCZ1dLSGlPZTNVeU1WTWc2azN3MXBWTnRUSHdqMWdEVjQwdjZWZ21TZkV6ZHp2M25nYldxUkFzSkNXaXg1czlyTlBLRlVrM1E=');
        $this->integrationId = env('PAYMOB_INTEGRATION_ID', '5434118');
        $this->iframeId = env('PAYMOB_IFRAME_ID', '452899');
        $this->hmacSecret = env('PAYMOB_HMAC_SECRET', 'CF09313F5BEEAFDA690BC7F1C656DEE0');
    }

    /**
     * Get callback URL
     *
     * @param string|null $customCallback
     * @return string
     */
    protected function getCallbackUrl($customCallback = null)
    {
        if ($customCallback) {
            return $customCallback;
        }

        return route('paymob.payment.callback');
    }

    /**
     * Initialize payment with Paymob
     *
     * @param array $item_data - Payment data including:
     *   - name: Package name
     *   - price: Package price
     *   - desc: Description
     *   - qty: Quantity
     *   - duration: Subscription duration in days
     *   - client_data: (optional) Array of client/gym data to pass through
     *   - callback_url: (optional) Custom callback URL
     * @return string|null Payment URL
     */
    public function payment($item_data = [])
    {
        try {
            // Step 1: Get authentication token
            $authToken = $this->getAuthToken();

            if (!$authToken) {
                throw new \Exception('Failed to get authentication token');
            }

            // Prepare merchant order ID with encoded data
            $merchantOrderId = $this->generateMerchantOrderId($item_data);

            // Step 2: Create order
            $order = $this->createOrder($authToken, $item_data, $merchantOrderId);

            if (!$order) {
                throw new \Exception('Failed to create order');
            }

            // Step 3: Get payment key with custom data
            $paymentKey = $this->getPaymentKey($authToken, $order, $item_data);

            if (!$paymentKey) {
                throw new \Exception('Failed to get payment key');
            }

            // Step 4: Return iframe URL
            return "https://accept.paymob.com/api/acceptance/iframes/{$this->iframeId}?payment_token={$paymentKey}";

        } catch (\Exception $e) {
            Log::error('Paymob Payment Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate merchant order ID with encoded data
     *
     * @param array $item_data
     * @return string
     */
    protected function generateMerchantOrderId($item_data)
    {
        $setting = Setting::first();

        // Prepare data to encode
        $orderData = [
            'timestamp' => time(),
            'duration' => $item_data['duration'] ?? 0,
            'package_name' => $item_data['name'] ?? '',
            'price' => $item_data['price'] ?? 0,
            'client_token' => $setting->token ?? '',
        ];

        // Add custom client data if provided
        if (isset($item_data['client_data']) && is_array($item_data['client_data'])) {
            $orderData = array_merge($orderData, $item_data['client_data']);
        }

        // Encode data as base64
        return base64_encode(json_encode($orderData));
    }

    /**
     * Decode merchant order ID
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
            Log::error('Failed to decode merchant order ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get authentication token from Paymob
     *
     * @return string|null
     */
    protected function getAuthToken()
    {
        try {
            $response = Http::withoutVerifying()->post('https://accept.paymob.com/api/auth/tokens', [
                'api_key' => $this->apiKey
            ]);

            if ($response->successful()) {
                return $response->json('token');
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Paymob Auth Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create order in Paymob
     *
     * @param string $authToken
     * @param array $item_data
     * @param string $merchantOrderId
     * @return array|null
     */
    protected function createOrder($authToken, $item_data, $merchantOrderId)
    {
        try {
            $setting = Setting::first();
            $totalAmount = $item_data['price'] + ((Constants::PAYMOB_TRANSACTION_FEES / 100) * $item_data['price']);

            // Convert to cents (Paymob uses cents)
            $amountCents = (int)($totalAmount * 100);

            $response = Http::withoutVerifying()->post('https://accept.paymob.com/api/ecommerce/orders', [
                'auth_token' => $authToken,
                'delivery_needed' => false,
                'amount_cents' => $amountCents,
                'currency' => 'EGP', // Change to your currency
                'merchant_order_id' => $merchantOrderId,
                'items' => [
                    [
                        'name' => $item_data['name'],
                        'amount_cents' => $amountCents,
                        'description' => $item_data['desc'],
                        'quantity' => $item_data['qty']
                    ]
                ]
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Paymob Order Creation Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get payment key from Paymob
     *
     * @param string $authToken
     * @param array $order
     * @param array $item_data
     * @return string|null
     */
    protected function getPaymentKey($authToken, $order, $item_data)
    {
        try {
            $setting = Setting::first();
            $totalAmount = $item_data['price'] + ((Constants::PAYMOB_TRANSACTION_FEES / 100) * $item_data['price']);

            // Convert to cents
            $amountCents = (int)($totalAmount * 100);

            $billingData = [
                'first_name' => $item['client_data']['gym_name'] ?? 'Gym',
                'last_name' => 'Customer',
                'email' => $item['client_data']['gym_email'] ?? 'gymmawy.com@gmail.com',
                'phone_number' => $item['client_data']['gym_phone'] ?? '01002509905',
                'apartment' => 'NA',
                'floor' => 'NA',
                'street' => 'NA',
                'building' => 'NA',
                'shipping_method' => 'NA',
                'postal_code' => 'NA',
                'city' => 'NA',
                'country' => 'EG',
                'state' => 'NA'
            ];

            $response = Http::withoutVerifying()->post('https://accept.paymob.com/api/acceptance/payment_keys', [
                'auth_token' => $authToken,
                'amount_cents' => $amountCents,
                'expiration' => 3600, // 1 hour
                'order_id' => $order['id'],
                'billing_data' => $billingData,
                'currency' => 'EGP', // Change to your currency
                'integration_id' => $this->integrationId
            ]);

            if ($response->successful()) {
                return $response->json('token');
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Paymob Payment Key Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Handle Paymob callback
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback(Request $request)
    {
        try {
            // Log callback data for debugging
            Log::info('Paymob Callback Received', $request->all());

            // Verify HMAC
            if (!$this->verifyHmac($request)) {
                Log::error('Paymob HMAC Verification Failed');
                sweet_alert()->error(trans('admin.done'), trans('admin.operation_failed'));
                return redirect(route('sw.listSwPayment'));
            }

            // Get callback data
            $success = $request->input('success');
            $pending = $request->input('pending');
            $errorOccured = $request->input('error_occured');
            $isVoided = $request->input('is_voided');
            $isRefunded = $request->input('is_refunded');
            $merchantOrderId = $request->input('merchant_order_id');
            $amountCents = $request->input('amount_cents');
            $transactionId = $request->input('id');
            $currency = $request->input('currency');
            $createdAt = $request->input('created_at');

            // Decode merchant order ID to get custom data
            $orderData = $this->decodeMerchantOrderId($merchantOrderId);

            if (!$orderData) {
                Log::error('Failed to decode merchant order ID: ' . $merchantOrderId);
                sweet_alert()->error(trans('admin.done'), trans('admin.operation_failed'));
                return redirect(route('sw.listSwPayment'));
            }

            // Check if payment is truly successful
            // Payment is successful only if: success=true AND pending=false AND error_occured=false AND is_voided=false AND is_refunded=false
            $isPaymentSuccessful = ($success == 'true' || $success === true)
                && ($pending == 'false' || $pending === false || $pending === null)
                && ($errorOccured == 'false' || $errorOccured === false || $errorOccured === null)
                && ($isVoided == 'false' || $isVoided === false || $isVoided === null)
                && ($isRefunded == 'false' || $isRefunded === false || $isRefunded === null);

            if ($isPaymentSuccessful) {
                // Extract data from decoded order
                $duration = $orderData['duration'] ?? 0;
                $packageName = $orderData['package_name'] ?? '';
                $price = $orderData['price'] ?? 0;
                $clientToken = $orderData['client_token'] ?? '';

                // Prepare invoice data with full details
                $invoiceData = [
                    'status' => TypeConstants::SUCCESS,
                    'payment_method' => TypeConstants::PAYMOB_TRANSACTION_FEES,
                    'duration' => $duration,
                    'response_code' => json_encode([
                        'transaction_id' => $transactionId,
                        'amount_cents' => $amountCents,
                        'currency' => $currency,
                        'success' => $success,
                        'pending' => $pending,
                        'error_occured' => $errorOccured,
                        'is_voided' => $isVoided,
                        'is_refunded' => $isRefunded,
                        'merchant_order_id' => $merchantOrderId,
                        'package_name' => $packageName,
                        'price' => $price,
                        'client_token' => $clientToken,
                        'order_data' => $orderData,
                        'full_response' => $request->all(),
                        'payment_date' => $createdAt,
                    ]),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];

                // Save payment invoice
                GymSWClientPaymentInvoice::insert($invoiceData);

                // Update subscription end date
                $this->successfulPayment($duration);

                Log::info('Paymob Payment Success', [
                    'transaction_id' => $transactionId,
                    'duration' => $duration,
                    'amount' => $amountCents / 100
                ]);

                sweet_alert()->success(trans('admin.done'), trans('admin.successfully_paid'));
                return redirect(route('sw.listSwPayment'));
            }

            // Payment failed
            Log::warning('Paymob Payment Failed', [
                'merchant_order_id' => $merchantOrderId,
                'response' => $request->all()
            ]);

            sweet_alert()->error(trans('admin.done'), trans('admin.something_wrong'));
            return redirect(route('sw.listSwPayment'));

        } catch (\Exception $e) {
            Log::error('Paymob Callback Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            sweet_alert()->error(trans('admin.done'), trans('admin.something_wrong'));
            return redirect(route('sw.listSwPayment'));
        }
    }

    /**
     * Handle successful payment
     *
     * @param int $duration
     * @return bool
     */
    public function successfulPayment($duration)
    {
        $setting = Setting::first();
        $package_duration = $duration;

        if ($package_duration) {
            if (Carbon::parse($setting->sw_end_date)->toDateString() > Carbon::now()->toDateString()) {
                $setting_inputs['sw_end_date'] = Carbon::parse($setting->sw_end_date)->addDays($package_duration)->toDateString();
            } else {
                $setting_inputs['sw_end_date'] = Carbon::now()->addDays($package_duration)->toDateString();
            }

            $setting->update($setting_inputs);
            Cache::store('file')->clear();
            return true;
        }

        return false;
    }

    /**
     * Verify HMAC signature
     *
     * @param Request $request
     * @return bool
     */
    protected function verifyHmac(Request $request)
    {
        if (!$this->hmacSecret) {
            return true; // Skip verification if HMAC secret is not set
        }

        $receivedHmac = $request->input('hmac');

        // Build the string to hash according to Paymob documentation
        $concatenatedString =
            $request->input('amount_cents') .
            $request->input('created_at') .
            $request->input('currency') .
            $request->input('error_occured') .
            $request->input('has_parent_transaction') .
            $request->input('id') .
            $request->input('integration_id') .
            $request->input('is_3d_secure') .
            $request->input('is_auth') .
            $request->input('is_capture') .
            $request->input('is_refunded') .
            $request->input('is_standalone_payment') .
            $request->input('is_voided') .
            $request->input('order') .
            $request->input('owner') .
            $request->input('pending') .
            $request->input('source_data_pan') .
            $request->input('source_data_sub_type') .
            $request->input('source_data_type') .
            $request->input('success');

        $calculatedHmac = hash_hmac('sha512', $concatenatedString, $this->hmacSecret);

        return hash_equals($calculatedHmac, $receivedHmac);
    }

    /**
     * Cancel payment
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel()
    {
        sweet_alert()->error(trans('admin.done'), trans('admin.something_wrong'));
        return redirect(route('sw.listSwPayment'));
    }
}

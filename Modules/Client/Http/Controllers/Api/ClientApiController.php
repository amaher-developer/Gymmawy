<?php

namespace Modules\Client\Http\Controllers\Api;

use Modules\Client\Models\Client;
use Modules\Client\Models\ClientSMSLog;
use Modules\Generic\Classes\Constants;
use Modules\Generic\Http\Controllers\Api\GenericApiController;
use Modules\Generic\Classes\SMS;
use Modules\Generic\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class ClientApiController extends GenericApiController
{

    public function sendSMS(){
        if(request('token') && request('phones') && request('message')){
            $phones = request('phones');
            $message = request('message');
            $phone_count = @(int)count(explode(',', $phones));
            $client = $this->getClient(request('token'));
            $smsBalance = @$client->sms_balance;
            if($smsBalance > ($phone_count*Constants::SMS_PRICE_INTERNAL)){
                $response = '';
                $status = 1;
                $sms = SMS::sms();
                // send sms
                $response = $sms->send($phones, $message);
                dd($response);
                $client->sms_balance = intval($smsBalance - $phone_count);
                $client->save();
                $this->return['data']['points'] = $client->sms_balance;
                ClientSMSLog::insert(['content' => $message, 'phones' => $phones, 'client_id' => $client->id, 'response' => @$response, 'status' => @$status]);
                return response()->json($this->return)->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
            }

            $this->return['error'] = true;
            return response()->json($this->return)->setStatusCode(Response::HTTP_PAYMENT_REQUIRED, Response::$statusTexts[Response::HTTP_PAYMENT_REQUIRED]);

        }
        $this->return['error'] = true;
        return response()->json($this->return)->setStatusCode(Response::HTTP_UNAUTHORIZED, Response::$statusTexts[Response::HTTP_UNAUTHORIZED]);

    }
    public function getSMSBalance(Request $request){
        $token = request('token');
        if($token){
            $client = $this->getClient($token);
            if($client){
                $smsBalance = (int)@$client->sms_balance;
                $this->return['data']['points'] = $smsBalance;
                return response()->json($this->return)->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
            }
        }
        $this->return['error'] = $request->all();
        return response()->json($this->return)->setStatusCode(Response::HTTP_UNAUTHORIZED, Response::$statusTexts[Response::HTTP_UNAUTHORIZED]);

    }

    private function getClient($token = null){
        $client = Client::where('token', $token)->first();
        if($client){
            return $client;
        }
        return null;
    }
//    private function getClient($token = null){
//        $clients = Client::all();
//        foreach ($clients as $client){
//            if(Hash::check($client->token, $token)){
//                return $client;
//            }
//        }
//        return null;
//    }
}

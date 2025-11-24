<?php

namespace Modules\Notification\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Generic\Classes\Constants;
use Illuminate\Support\Facades\Log;
use Modules\Notification\Models\Push_tokens;

class FirebaseApiController extends Controller
{
    public function addTokenToTopic($token, $topic = 'all')
    {
        return $this->sendRequest('POST', "https://iid.googleapis.com/iid/v1/{$token}/rel/topics/{$topic}", []);
    }

    public function pushToTopic($data, $topic = 'all')
    {
        if($topic == Constants::IOS){
            $body = [
                'to' => "/topics/{$topic}",
                'data' => $data,
                'notification' => $data,
                'priority' => 'high'
            ];
        }else if($topic == Constants::WEB){
            $data['icon'] = asset('resources/assets/front/img/logo/favicon.ico');
            $data['receiver'] = 'erw';
            $data['sound'] = 'mySound';

            $body = array
            (
                'to' => "/topics/{$topic}",
                'data' => $data,
                'notification' => $data,
            );
        }else{
            $body = [
                'to' => "/topics/{$topic}",
                'data' => $data,
                'priority' => 'high'
            ];
        }


        return $this->sendRequest('POST', 'https://fcm.googleapis.com/fcm/send', $body);
    }

    public function push($userIds, $data)
    {
        $tokens = Push_tokens::select('token', 'device_type')->whereIn('user_id', (array)$userIds)->get();
        foreach($tokens as $token)
            $result = $this->pushToTokens((array)$token->token, $data, $token->device_type);

        return $result;
//        $tokens = Push_tokens::select('token')->whereIn('user_id', (array)$userIds)->pluck('token')->toArray();
//        if ($tokens)
//            return $this->pushToTokens($tokens, $data);
    }

    public function pushToTokens(array $tokens, $data, $device_type = Constants::ANDROID)
    {
        $body = [
            'registration_ids' => $tokens,
            'data' => $data
        ];
        if(in_array($device_type, [Constants::IOS, Constants::WEB]))
            $body['notification'] = $data;
        return $this->sendRequest('POST', 'https://fcm.googleapis.com/fcm/send', $body);
    }

    private function sendRequest($method, $url, $post_fields)
    {
        $ch = curl_init();
        $authorization = 'key=AAAAcd8VT4I:APA91bFb3w3Y1M2ULbG77tMVq4HMsJ7pW8j3tC63ZAkZKuNO7h2G5PeWfJxwcxoF0mPYarMZE-IX7SRtYKPHUvs5AVNjj1A4nyzdTRVaLN_gyw5ce4AeLHzhF-giybm-6921jSqA0Kee';
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_ENCODING => "",
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => json_encode($post_fields),
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "authorization: {$authorization}",
                "cache-control: no-cache",
                "content-type: application/json"
            ],
            CURLOPT_SSL_VERIFYPEER => FALSE,
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
//        Log::info(_METHOD_, [$response]);
        return $response;
    }

}

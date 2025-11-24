<?php

namespace Modules\SmsGateway\Http\Controllers\Admin;

use Modules\Generic\Http\Controllers\Admin\GenericAdminController;

class AlaAdminController extends GenericAdminController
{

    public static function sentSms($to = null, $content = 'hello', $apiKey = 'API73383465094'){
        $password = 'sZf7yzz5U3';
        $username = 'SAU73396814843';
        $sender = 'Yacune';
        if($to && $content) {

            foreach ((array)$to as $t) {
                $mob = $t;
                if(substr($mob, 0, 2) == "01") $t = str_replace("01", "201", $mob);
                elseif(substr($mob, 0, 2) == "05") $t = str_replace("05", "9665", $mob);
                else $t = $mob;

                $url = 'http://api.smsala.com/api/SendSMS?api_id=' . $apiKey . '&api_password='.$password.'&sms_type=T&encoding=U&sender_id='.$sender.'&phonenumber='.$to.'&textmessage=' .  urlencode($content) ;

                $ch = curl_init($url);

                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                $result = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                $smsClickatellResponse['http_code'] = $httpCode;
                $smsClickatellResponse['result'] = json_decode($result);

                //return $httpCode;
            }
        }
    }







}

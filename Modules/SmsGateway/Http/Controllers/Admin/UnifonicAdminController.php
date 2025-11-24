<?php

namespace Modules\SmsGateway\Http\Controllers\Admin;

use Modules\Generic\Http\Controllers\Admin\GenericAdminController;

class UnifonicAdminController extends GenericAdminController
{

    public static function sentSms($to = null, $content = 'hello', $apiKey = 'jGY6nGd2Tr2n0LCKX88Wbg=='){
        $userid = '';
        $password = '';
        $senderid = 'Yacune';
        if($to && $content) {

            foreach ((array)$to as $t) {
                $mob = $t;
                if(substr($mob, 0, 2) == "01") $t = str_replace("01", "201", $mob);
                elseif(substr($mob, 0, 2) == "05") $t = str_replace("05", "9665", $mob);
                else $t = $mob;

                $url = 'http://api.unifonic.com/wrapper/sendSMS.php?userid='.$userid.'&password='.$password.'&msg='.urlencode($content).'&sender='.$senderid.'&to='.$to.'&encoding=UTF8' ;

                $ch = curl_init($url);

                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                $result = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                $smsUnifonicResponse['http_code'] = $httpCode;
                $smsUnifonicResponse['result'] = json_decode($result);

                //return $httpCode;
            }
        }
    }




}

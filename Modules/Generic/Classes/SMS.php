<?php

namespace Modules\Generic\Classes;



class SMS  {

    function __construct()
    {

    }

    public static function sms(){
        return self::SMSEG();
    }
    public static function validate($username){
        if($username && (strpos($username, Constants::KUWAIT_PHONE_PREFIX) === 0)){
            return self::SMSEG();
        }else{
            return self::SMSEG();
        }
    }
    public static function SMSEG()
    {
        return new SMSEG();
    }

}

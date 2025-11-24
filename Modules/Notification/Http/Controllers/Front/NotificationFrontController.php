<?php

namespace Modules\Notification\Http\Controllers\Front;

use Modules\Generic\Classes\Constants;
use Modules\Generic\Http\Controllers\Front\GenericFrontController;
use Modules\Notification\Http\Controllers\Api\FirebaseApiController;
use Modules\Notification\Models\Push_tokens;

class NotificationFrontController extends GenericFrontController
{

    public function __construct()
    {

    }

    public function create(){
        $token = @request('fcm_token');
        if($token){
            Push_tokens::updateOrCreate([
                'device_type' => Constants::WEB,
                'token' => $token,
            ], [
                'device_type' => Constants::WEB,
                'token' => $token,
                'user_id' => @$this->user->id,
            ]);
            (new FirebaseApiController())->addTokenToTopic($token, Constants::WEB);

            return 'true'.$token;
        }
        return 'false'.$token;
    }


}

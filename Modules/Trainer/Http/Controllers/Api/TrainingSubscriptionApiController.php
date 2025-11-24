<?php

namespace Modules\Trainer\Http\Controllers\Api;

use Modules\Client\Models\TrainingClient;
use Modules\Generic\Classes\Constants;
use Modules\Generic\Http\Controllers\Api\GenericApiController;
use Modules\Notification\Http\Controllers\Api\FirebaseApiController;
use Modules\Notification\Models\PushNotification;
use Modules\Trainer\Http\Resources\TrainingSubscriptionResource;
use Modules\Trainer\Models\TrainingSubscription;

class TrainingSubscriptionApiController extends GenericApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function subscriptions(){
        if(!$this->validateApiRequest([]))
            return $this->response;

        $client = TrainingClient::with('subscriptions');
        $client = $client->where('user_id', $this->api_user->id);
        $client = $client->first();

        $this->return['subscriptions'] = @$client->subscriptions ?  TrainingSubscriptionResource::collection($client->subscriptions) : [];
        return $this->successResponse();
    }
    public function subscription(){
        if(!$this->validateApiRequest(['id']))
            return $this->response;

        $id = request('id');
        $subscription = TrainingSubscription::with('client');
        $subscription = $subscription->where('id', $id);
        $subscription = $subscription->first();

        $this->return['subscription'] = @$subscription ? new  TrainingSubscriptionResource($subscription) : '';
        return $this->successResponse();
    }

    public function checkForSubscription(){
        $phone = request('phone');
        $code = request('code');
        $lang = request('lang') ? request('lang') : 'ar';
        if(!$this->validateApiRequest(['code', 'phone']))
            return $this->response;

        $subscription =  TrainingClient::where(['code' => $code, 'phone' => $phone])->first();
        if($subscription){
            $subscription->user_id = $this->api_user->id;
            $subscription->lang = $lang;
            $subscription->save();
            $this->return['subscription'] = $subscription;
            $this->return['message'] =trans('global.subscription_add_successfully');

            // notification welcome for subscription from gym side
//            $this->sendNotification($subscription, trans('sw.notification_welcome_msg', ['name' => $subscription->name, 'membership' => ($lang == 'ar' ? $subscription->subscription_name_ar : $subscription->subscription_name_en)]));
            // end notification
            return $this->successResponse();
        }
        return $this->falseResponse(trans('global.subscription_not_found'));
    }


    private function sendNotification($subscription = null, $msg = null){
        if($subscription->user_id && $msg) {

            $notification_data = [
                'title' => '@' . $subscription->gym->name,
                'image' => $subscription->gym->image_thumbnail,
                'id' => $subscription->user_id,
                'body' => $msg,
                'type' => (string)Constants::NOTIFICATION_MESSAGE_TYPE
            ];
            $push = (new FirebaseApiController())->push($subscription->user_id, $notification_data);

            $response = json_decode($push);

            if (@$response->success) {
                PushNotification::create([
                    'body' => $notification_data,
                    'user_id' => $subscription->user_id,
                    'notification_id' => @$response->results[0]->message_id
                ]);
                return true;
            }
        }
        return false;
    }


}

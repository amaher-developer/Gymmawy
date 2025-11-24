<?php

namespace Modules\Gym\Http\Controllers\Api;

use Modules\Client\Models\Client;
use Modules\Generic\Classes\Constants;
use Modules\Generic\Http\Controllers\Api\GenericApiController;
use Modules\Gym\Http\Resources\GymSubscriptionResource;
use Modules\Gym\Models\GymSubscription;
use Modules\Notification\Http\Controllers\Api\FirebaseApiController;
use Modules\Notification\Models\PushNotification;
use Modules\Software\Classes\TypeConstants;
use Milon\Barcode\DNS1D;

class SubscriptionApiController extends GenericApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function subscriptions(){

        if(!$this->validateApiRequest([]))
            return $this->response;

        $subscriptions = GymSubscription::select('id', 'phone', 'name', 'code', 'gym_id', 'user_id', 'client_id');
        $subscriptions = $subscriptions->with('gym', 'client');
        $subscriptions = $subscriptions->where('user_id', $this->api_user->id);
        $subscriptions = $subscriptions->get();


        $this->return['subscriptions'] = $subscriptions ?  GymSubscriptionResource::collection($subscriptions) : '';
        return $this->successResponse();
    }
    public function deleteSubscription(){

        $phone = request('phone');
        $code = request('code');
        $id = request('id');
        if(!$this->validateApiRequest(['id']))
            return $this->response;

        $subscription =  GymSubscription::where('id', $id)->first();
        if($subscription){
            GymSubscription::where('id', $subscription->id)->update(['user_id' => null]);
            $this->return['message'] = trans('global.subscription_delete_successfully');
            return $this->successResponse();
        }
        return $this->falseResponse(trans('global.subscription_not_found'));

    }
    public function checkForSubscription(){
        $phone = request('phone');
        $code = request('code');
        $lang = request('lang') ? request('lang') : 'ar';
        if(!$this->validateApiRequest(['code', 'phone']))
            return $this->response;

        $subscription =  GymSubscription::with('gym')->where(['code' => $code, 'phone' => $phone])->first();
        if($subscription){
            $code = sprintf("%020d", $code);
            $qrcodes_folder = base_path('uploads/barcodes/');
            $d = new DNS1D();
            $d->setStorPath($qrcodes_folder);
            $d->getBarcodePNGPath($code, Constants::BarcodeType);

            $subscription->user_id = $this->api_user->id;
            $subscription->lang = $lang;
            $subscription->save();
//            GymSubscription::where('id', $subscription->id)->update(['user_id' => $this->api_user->id]);
            $this->return['subscription'] = new GymSubscriptionResource($subscription);
            $this->return['message'] =trans('global.subscription_add_successfully');

            // notification welcome for subscription from gym side
            $this->sendNotification($subscription, trans('sw.notification_welcome_msg', ['name' => $subscription->name, 'membership' => ($lang == 'ar' ? $subscription->subscription_name_ar : $subscription->subscription_name_en)]));
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

    public function sendNotificationToMemberFromSW()
    {
        $code = request('code');
        $phone = request('phone');
        $msg = request('msg');
        $client_token = request('client_token');

        if(!$this->validateApiRequest(['phone', 'code', 'msg', 'client_token']))
            return $this->response;

        $client = Client::where('token', $client_token)->first();

        if($client){
            $subscription =  GymSubscription::with('gym')->where(['code' => $code, 'phone' => $phone, 'gym_id' => $client->gym_id])->first();
            if($subscription && $subscription->user_id){
                if($this->sendNotification($subscription, $msg)){
                    $this->return['success'] = true;
                    return $this->successResponse();
                }
            }
        }
        return $this->falseResponse();
    }

    public function addFromSW(){
        $name = request('name');
        $phone = request('phone');
        $code = request('code');
        $type = request('type');
        $getSubscription = [];
        if(@request('subscription_name_ar'))    $getSubscription['subscription_name_ar'] = @request('subscription_name_ar');
        if(@request('subscription_name_en'))    $getSubscription['subscription_name_en'] = @request('subscription_name_en');
        if(@request('workouts'))    $getSubscription['workouts'] = @(int)request('workouts');
        if(@request('visits'))    $getSubscription['visits'] = @(int)request('visits');
        if(@request('amount_remaining'))    $getSubscription['amount_remaining'] = @(float)request('amount_remaining');
        if(@request('joining_date'))    $getSubscription['joining_date'] = @request('joining_date');
        if(@request('expire_date'))    $getSubscription['expire_date'] = @request('expire_date');


        $client_token = request('client_token');

        if(!$this->validateApiRequest(['phone', 'code', 'name', 'client_token']))
            return $this->response;

        $client = Client::where('token', $client_token)->first();
        if($client){
            $subscription = GymSubscription::where(['code' => $code, 'phone' => $phone, 'gym_id' => $client->gym_id])->first();
            $data = ['code' => $code, 'phone' => $phone, 'name' => $name, 'gym_id' => $client->gym_id, 'client_id' => $client->id];
            if(count($getSubscription) > 0)
                $data = array_merge($data, $getSubscription);

            if($subscription)
                GymSubscription::where('id', $subscription->id)->update($data);
            else
                GymSubscription::create($data);

            // if renew member send notification
            if($type == 1){
                $this->sendNotification($subscription, trans('sw.notification_renew_msg', ['name' => @$subscription->name, 'membership' => ($subscription->lang == 'ar' ? $subscription->subscription_name_ar : $subscription->subscription_name_en)]));
            }

            $this->return['subscription'] = true;
            return $this->successResponse();
        }

        $this->return['subscription'] = false;
        return $this->falseResponse();
    }

    public function listOfSubscriptions(){
        $client_token = request('client_token');

        if(!$this->validateApiRequest(['client_token']))
            return $this->response;
        $client = Client::where('token', $client_token)->first();
        if($client) {
            $subscriptions = GymSubscription::where(['client_id' => $client->id])->where('user_id', '!=', '')->get();

            $this->return['subscriptions'] = $subscriptions ?  GymSubscriptionResource::collection($subscriptions) : '';
            return $this->successResponse();
        }

        return $this->falseResponse();
    }
}

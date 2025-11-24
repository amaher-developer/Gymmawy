<?php

namespace Modules\Notification\Http\Controllers\Admin;

use Modules\Generic\Classes\Constants;
use Modules\Item\Models\Item;
use Modules\Item\Models\Category;
use Modules\Notification\Http\Controllers\Api\FirebaseApiController;
use Modules\Notification\Http\enums\NotificationType;
use Modules\Notification\Http\Requests\NotificationRequest;
use Modules\Generic\Http\Controllers\Admin\GenericAdminController;
use Modules\Notification\Models\PushNotification;

class NotificationAdminController extends GenericAdminController
{

    public function index()
    {
        return view('notification::Admin.notification_admin_list', [
            'title' => 'Notifications',
            'notifications' => PushNotification::orderBy('id', 'DESC')->get(),
        ]);
    }

    public function create()
    {
        $title = 'Create Notification';
        return view('notification::Admin.notification_admin_form', [
            'title' => $title
        ]);
    }

    public function push(NotificationRequest $request)
    {
        $request->validate(['title' => 'required', 'type' => 'required']);
        $data['title'] = $request->title;
        $data['type'] = $request->type;
        $data['body'] = $request->body;

        $data['image'] = 'https://gymmawy.com/resources/assets/front/img/logo/default.png';
        $data['sound'] = 'default';
        $data['badge'] = '1';
        $data['e'] = 1;

        $getUserIds = $request->user_ids;
        $user_ids = [];
        if(isset($getUserIds)){
            $user_ids = explode(',', $getUserIds);
        }

        switch ($request->type) {
            case NotificationType::external_url:
                $data['url'] = $request->url;
                break;
        }

//        if ($request->test) {
//            $push = OneSignalController::notifySendToUsersByUserIds([1482], $data);
//        } else {
//            $push = OneSignalController::notifySendToAllUsers($data);
//        }



        foreach([Constants::ANDROID, Constants::IOS, Constants::WEB] as $device_type) {
            if ($request->test) {
                $push = (new FirebaseApiController())->push(1482, [
                    'title' => 'Admin reply ticket',
                    'id' => 1482,
                    'body' => '',
                    'type' => 2
                ]);
            } else {
                $push = (new FirebaseApiController())->pushToTopic($data, $device_type);
            }
        }

        $response = json_decode($push);


        if (@$response->message_id) {
            if(count($user_ids) > 0){
                foreach ($user_ids as $user_id){
                    PushNotification::create([
                        'body' => $data,
                        'user_id' => $user_id,
                        'notification_id' => $response->message_id
                    ]);
                }
            }else{
                PushNotification::create([
                    'body' => $data,
                    'notification_id' => $response->message_id
                ]);
            }

            $request->session()->flash('notification_recipients', $response->message_id);
            sweet_alert()->success('Done', 'Notification Sent Successfully');
            return redirect()->back();
        } else {
            $request->session()->flash('notification_error', $push);
            sweet_alert()->info('Error', 'Something went wrong');
            return redirect()->back()->withInput();
        }
    }

    public function show(PushNotification $notification)
    {
        $stats = OneSignalController::getNotificationStats($notification->notification_id);
        return view('notification::Admin.notification_admin_show', [
            'title' => $notification->title,
            'stats' => json_decode($stats),
            'notification' => $notification
        ]);
    }

}

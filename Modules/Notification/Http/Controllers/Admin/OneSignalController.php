<?php

namespace Modules\Notification\Http\Controllers\Admin;

use Modules\Generic\Http\Controllers\Api\GenericApiController;
use Modules\Notification\Models\Push_tokens;

class OneSignalController extends GenericApiController
{

    private static $app_id = 'b6230027-2868-44b5-b5fc-96bd428959de';
    private static $rest_api_key = 'MGIwM2VmM2EtMjJmNy00ZWZkLTg2ZWMtNzkxOGU3NmJlNjY0';
//    private static $user_auth_key = 'MDUzZjEzM2MtMDNmMC00NWU4LTkzNDEtYzJjN2JmZDY4ZWEw';

    function __construct()
    {
        parent::__construct();
    }

    public static function getNotificationStats($notificationId)
    {
        $app_id = self::$app_id;
        $rest_api_key = self::$rest_api_key;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications/{$notificationId}?app_id={$app_id}");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
            "Authorization: Basic {$rest_api_key}"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public static function notifySendToAllUsers($data)
    {
        $OSData = array(
            'title' => $data['title'],
            'type' => $data['type'],
            'data' => $data
        );

        $content = array(
            "en" => $data['title']
        );

        $fields = array(
            'app_id' => self::$app_id,
            'included_segments' => array('All'),
            'contents' => $content,
            'data' => $OSData,
        );

        $fields = json_encode($fields);
        $rest_api_key = self::$rest_api_key;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            "Authorization: Basic $rest_api_key"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;

    }

    public static function addUserToOneSignal($token, $device_type)
    {
        $fields = array(
            'app_id' => self::$app_id,
            'identifier' => $token,
            'language' => "en",
            'device_type' => $device_type,
            'test_type' => 1
        );

        $fields = json_encode($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/players");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response);

        return $response;
    }

    public static function updateUserToOneSignal($oneSignalToken, $token)
    {
        $fields = array(
            'app_id' => self::$app_id,
            'identifier' => $token
        );
        $fields = json_encode($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/players/$oneSignalToken");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response);

        return $response;
    }

    public static function softDeleteUserByTag($oneSignalToken)
    {
        $fields = array(
            'app_id' => self::$app_id,
            'tags' => ['deleted' => TRUE]
        );
        $fields = json_encode($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/players/$oneSignalToken");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response);

        return $response;
    }

    public static function notifySendToUsersByUserIds($user_ids, $data)
    {
        $user_tokens = OneSignalController::getOneSignalTokensByUserIds($user_ids);
        return OneSignalController::notifySendToUserByOneSignalToken($user_tokens, $data);
    }

    public static function getOneSignalTokensByUserIds($user_ids)
    {
        $user_ids = is_array($user_ids) ? $user_ids : array($user_ids);
        return $users_tokens = Push_tokens::whereIn('user_id', $user_ids)->pluck('one_signal_token')->toArray();
    }

    public static function notifySendToUserByOneSignalToken($oneSignalToken, $data)
    {
        $OSData = array(
            'title' => $data['title'],
            'type' => $data['type'],
            'data' => $data
        );

        $content = array(
            "en" => $data['title']
        );

        $fields = array(
            'app_id' => self::$app_id,
            'include_player_ids' => $oneSignalToken,
            'contents' => $content,
            'data' => $OSData,
        );

        $fields = json_encode($fields);
        $rest_api_key = self::$rest_api_key;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            "Authorization: Basic $rest_api_key"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;

    }

    public static function getAllDevices($offset = 0, $limit = 300)
    {
        $app_id = self::$app_id;
        $rest_api_key = self::$rest_api_key;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/players?app_id={$app_id}&limit={$limit}&offset={$offset}");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
            "Authorization: Basic {$rest_api_key}"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function scanDevices()
    {
        $offest = 0;
        $limit = 300;
        do {
            $devices = json_decode(self::getAllDevices($offest, $limit));
            foreach ($devices->players as $player) {
                if ($player->invalid_identifier && (!@$player->tags->deleted)) {
                    self::softDeleteUserByTag($player->id);
                    Push_tokens::whereOneSignalToken($player->id)->delete();
                }
            }

            $pages = ceil($devices->total_count / $limit);
            $haveMore = ($pages > $offest + 1) ? TRUE : FALSE;
            $offest++;
        } while ($haveMore);
    }

    function updateUser($oneSignalToken)
    {
        $playerID = $oneSignalToken;
        $fields = array(
            'app_id' => self::$app_id,
        );
        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://onesignal.com/api/v1/players/' . $playerID);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        $resultData = json_decode($response, true);
        echo $resultData;
    }

}

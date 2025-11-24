<?php

namespace Modules\Mailchimp\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use stdClass;

class MailchimpAdminController extends Controller
{
    // local config
//    public static $newsletterListId = '53c7853d49';
//    protected $mailChimpResponse = array('http_code' => '', 'result' => '');
//    protected $apiKey = '7187a2c34a89d7baa7b17ea682ed6f80-us14';
//    protected $from = 'noreply@internetplus.biz';
//    protected $account_email = 'engkhaledmohamed1991@gmail.com ';



    // server config
    public static $newsletterListId = 'fdbca68b1f';
    protected $apiKey = 'a96bea8ca87aad7409c44cf5f5d33229-us17'; //domain
    protected $from = 'info@yacune.com'; // domain
    protected $account_email = 'ghanem@yacune.com '; //account email

    public function AddList($name)
    {
        // data=['name']

        $dataCenter = substr($this->apiKey, strpos($this->apiKey, '-') + 1);
        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists';
        $campaign_defaults_object = new stdClass();
        $campaign_defaults_object->from_name = 'Yacune - ' . $name;
        $campaign_defaults_object->from_email = $this->from;
        $campaign_defaults_object->subject = 'Promotion letter for ' . $name;
        $campaign_defaults_object->language = 'english';


        $contact_object = new stdClass();
        $contact_object->company = 'Yacune';
        $contact_object->address1 = 'Cairo';
        $contact_object->city = 'Cairo';
        $contact_object->state = 'Cairo';
        $contact_object->zip = '11511';
        $contact_object->country = 'EGY';

        $json = json_encode([
            'name' => $name,
            'contact' => $contact_object,
            'campaign_defaults' => $campaign_defaults_object,
            'notify_on_subscribe' => $this->account_email,
            'notify_on_unsubscribe' => $this->account_email,
            'email_type_option' => false,
            'permission_reminder' => 'you made subscription to our list through Yacune website subscription form'

        ]);

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        $mailChimpResponse['http_code'] = $httpCode;
        $mailChimpResponse['result'] = json_decode($result);
        return $mailChimpResponse;
    }


    public function AddCampaign($data)
    {
        // data=['name','list_id']
        $dataCenter = substr($this->apiKey, strpos($this->apiKey, '-') + 1);
        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/campaigns';


        $recipients = new stdClass();
        $recipients->list_id = $data['list_id'];


        $settings = new stdClass();
        $settings->subject_line = $data['name'];
        $settings->title = $data['name'] . rand(1, 654);
        $settings->from_name = $data['name'];
        $settings->reply_to = $this->from;


        $json = json_encode([
            'recipients' => $recipients,
            'settings' => $settings,
            'type' => 'regular'

        ]);

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        $mailChimpResponse['http_code'] = $httpCode;
        $mailChimpResponse['result'] = json_decode($result);
        return $mailChimpResponse;
    }


    public function AddContentToCampaign($data)
    {
        // data=['content','campaign_id','type']

        $json = '';
        $dataCenter = substr($this->apiKey, strpos($this->apiKey, '-') + 1);
        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/campaigns/' . $data['campaign_id'] . '/content';
        if ($data['type'] == 'newsletter')
            $json = json_encode(['html' => $this->prepareNewsletterContent($data)]);
        else
            $json = json_encode(['html' => $this->prepareCampaignContent($data)]);

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        $mailChimpResponse['http_code'] = $httpCode;
        $mailChimpResponse['result'] = json_decode($result);
        return $mailChimpResponse;
    }

    public function prepareNewsletterContent($data)
    {
        return view('mailchimp::Admin.newsletter_template', $data)->render();
    }

    public function prepareCampaignContent($data)
    {
        return view('mailchimp::Admin.newsletter_template', $data)->render();

    }

    public function DeleteList($listId)
    {
        $dataCenter = substr($this->apiKey, strpos($this->apiKey, '-') + 1);
        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId;

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $mailChimpResponse['http_code'] = $httpCode;
        $mailChimpResponse['result'] = json_decode($result);
        return $mailChimpResponse;
    }

    public function AddToList($email, $listId)
    {

        // data=['email','status','firstname','lastname']
        $memberId = md5(strtolower($email));
        $dataCenter = substr($this->apiKey, strpos($this->apiKey, '-') + 1);
        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;

        $json = json_encode([
            'email_address' => $email,
            'status_if_new' => 'subscribed',
        ]);


        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $mailChimpResponse['http_code'] = $httpCode;
        $mailChimpResponse['result'] = json_decode($result);

        return $mailChimpResponse;
    }

    public function CheckInList($email, $listId)
    {
        $memberId = md5(strtolower($email));
        $dataCenter = substr($this->apiKey, strpos($this->apiKey, '-') + 1);
        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->apiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
        $mailChimpResponse['http_code'] = $httpCode;
        $mailChimpResponse['result'] = json_decode($result);
        return $mailChimpResponse;
    }

    public function RemoveFromList($email, $listId)
    {

        $memberId = md5(strtolower($email));
        $dataCenter = substr($this->apiKey, strpos($this->apiKey, '-') + 1);
        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $mailChimpResponse['http_code'] = $httpCode;
        $mailChimpResponse['result'] = json_decode($result);
        return $mailChimpResponse;
    }

    public function RemoveFromAllSubscribedList($email, $lists)
    {
        $memberId = md5(strtolower($email));
        $dataCenter = substr($this->apiKey, strpos($this->apiKey, '-') + 1);
        $mailChimpResponse = array();
        foreach ($lists as $listId) {
            $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;

            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->apiKey);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $mailChimpResponse['http_code'] = $httpCode;
            $mailChimpResponse['result'] = json_decode($result);
        }
        return $mailChimpResponse;
    }

    public function SendTestMailCampaign($campaign_id)
    {
//        $campaign_id=49523;

        $dataCenter = substr($this->apiKey, strpos($this->apiKey, '-') + 1);
        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/campaigns/' . $campaign_id . '/actions/test';


        $test_emails = array('callmenow@gmail.com', $this->account_email);
        $json = json_encode([
            'test_emails' => $test_emails,
            'send_type' => 'html'
        ]);

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        $mailChimpResponse['http_code'] = $httpCode;
        $mailChimpResponse['result'] = json_decode($result);
        return $mailChimpResponse;
    }

    public function SendMailCampaign($campaign_id)
    {
//        $campaign_id=49523;

        $dataCenter = substr($this->apiKey, strpos($this->apiKey, '-') + 1);
        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/campaigns/' . $campaign_id . '/actions/send';

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        $mailChimpResponse['http_code'] = $httpCode;
        $mailChimpResponse['result'] = json_decode($result);
        return $mailChimpResponse;
    }


}

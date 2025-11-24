<?php

namespace Modules\Notification\Http\Controllers\Admin;

use Modules\Generic\Http\Controllers\Admin\GenericAdminController;
use Modules\Item\Http\enums\ItemStatus;
use Modules\Item\Models\Item;
use Modules\Location\Models\City;
use Modules\Location\Models\District;
use Modules\Mailchimp\Http\Controllers\Admin\MailchimpAdminController;
use Modules\Notification\Http\Requests\NewsletterRequest;
use Modules\Notification\Models\Newsletter;

class NewsletterAdminController extends GenericAdminController
{
    public function index()
    {
        $title = 'newsletters List';
        if (request('trashed')) {
            $newsletters = Newsletter::onlyTrashed()->paginate(50);
        } else {
            $newsletters = Newsletter::paginate(50);
        }

        return view('notification::Admin.newsletter_admin_list', compact('newsletters', 'title'));
    }

    public function create()
    {

        $hot_deals = Item::with(['item_type.parent', 'district.city'])->where('status', ItemStatus::approved)
            ->orderBy('hot_deals_factor', 'desc')->paginate(20);

        $title = 'Create Newsletter';
        return view('notification::Admin.newsletter_admin_form', ['newsletter' => new Newsletter(), 'title' => $title, 'items' => $hot_deals]);
    }

    public function store(NewsletterRequest $request)
    {

        $sorting_arr = $request->input('chosen_item');
        $newsletter_items = Item::with(['item_type.parent', 'district.city'])
            ->find($sorting_arr)->sortBy(function ($person, $key) use ($sorting_arr) {
                return array_search($person->id, $sorting_arr);
            });

        $mailChimpCampaignResponse = $this->mailChimpManager->AddCampaign(array('name' => 'Newsletter'. "-" . $this->settings->name_en, 'list_id' => MailchimpAdminController::$newsletterListId));
        if ($mailChimpCampaignResponse['http_code'] == 200) {

            $mailChimpResponse = $this->mailChimpManager->AddContentToCampaign(array('type' => 'newsletter', 'unsubscribe_list' => 'newsletter', 'settings' => $this->settings, 'items' => $newsletter_items, 'msg' => "test test", 'campaign_id' => $mailChimpCampaignResponse['result']->id));
            if ($mailChimpResponse['http_code'] == 200) {
//                    $mailChimpSendMailResponse = $mailChimpManager->SendTestMailCampaignMailchimp($mailChimpCampaignResponse['result']->id);
                $mailChimpSendMailResponse = $this->mailChimpManager->SendMailCampaign($mailChimpCampaignResponse['result']->id);
                if ($mailChimpSendMailResponse['http_code'] = 204) {
                    sweet_alert()->success('Done', trans('global.successfully_send'));
                } else {
                    sweet_alert()->error('error', trans('global.unsuccessfully_send'));
                }
            } else {

                sweet_alert()->error('error', trans('global.unsuccessfully_send'));
            }
        } else {

            sweet_alert()->error('error', trans('global.unsuccessfully_send'));
        }
        return redirect(route('createNewsletter'));
    }


    public function select_area()
    {

        $title = 'Select District For Promotion Letter';
        return view('notification::Admin.promotion_letter_select_area',
            ['districts' => District::all()
                , 'cities' => City::all(),
                'title' => $title]);
    }

    public function create_promotion_letter()
    {
        $district_id = request('district_id');
        $hot_deals = Item::with(['item_type.parent', 'district.city'])->where('status', ItemStatus::approved)
            ->where('district_id', $district_id)->orderBy('id', 'desc')->paginate(20);

        $title = 'Create Promotion Letter';
        return view('notification::Admin.promotion_admin_form', ['title' => $title, 'district_id' => $district_id, 'items' => $hot_deals]);
    }


    public function send_promotion_letter(NewsletterRequest $request)
    {

        $sorting_arr = $request->input('chosen_item');
        $promotion_letter = $request->input('promotion_letter');
        $district_id = $request->input('district_id');

        $district = District::find($district_id);
        $newsletter_items = Item::with(['item_type.parent', 'district.city'])
            ->find($sorting_arr)->sortBy(function ($person, $key) use ($sorting_arr) {
                return array_search($person->id, $sorting_arr);
            });
        $mailChimpCampaignResponse = $this->mailChimpManager->AddCampaign(array('name' => " Promotion Litter". "-" . $this->settings->name_en, 'list_id' => $district->mailchimp_list_id));
        if ($mailChimpCampaignResponse['http_code'] == 200) {

            $mailChimpResponse = $this->mailChimpManager->AddContentToCampaign(array('type' => 'promotion_letter', 'unsubscribe_list' => $district->mailchimp_list_id, 'settings' => $this->settings, 'msg' => $promotion_letter, 'items' => $newsletter_items, 'campaign_id' => $mailChimpCampaignResponse['result']->id));
            if ($mailChimpResponse['http_code'] == 200) {
//                    $mailChimpSendMailResponse = $mailChimpManager->SendTestMailCampaignMailchimp($mailChimpCampaignResponse['result']->id);
                $mailChimpSendMailResponse = $this->mailChimpManager->SendMailCampaign($mailChimpCampaignResponse['result']->id);
                if ($mailChimpSendMailResponse['http_code'] = 204) {
                    sweet_alert()->success('Done', trans('global.successfully_send'));
                } else {
                    sweet_alert()->error('error', trans('global.unsuccessfully_send'));
                }
            } else {

                sweet_alert()->error('error', trans('global.unsuccessfully_send'));
            }
        } else {

            sweet_alert()->error('error', trans('global.unsuccessfully_send'));
        }
        return redirect(route('selectAreaForPromotionLetter'));
    }

}

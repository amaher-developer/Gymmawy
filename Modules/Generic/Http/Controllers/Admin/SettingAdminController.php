<?php

namespace Modules\Generic\Http\Controllers\Admin;

use Modules\Access\Models\User;
use Modules\Generic\Http\Requests\SettingRequest;
use Modules\Generic\Models\Contact;
use Modules\Generic\Models\Setting;
use Modules\Generic\Classes\WA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;

class SettingAdminController extends GenericAdminController
{

    public $consume_user_count = 0;
    public $consume_message_count = 0;

    public function edit()
    {
        $title = 'Update Content';
        return view('generic::Admin.setting_admin_form', ['title'=>$title]);
    }

    public function update(SettingRequest $request, Setting $setting)
    {
        $setting_inputs = $this->prepare_inputs($request->except(['_token']));
       $setting->update($setting_inputs);
        Cache::store('file')->clear();
        sweet_alert()->success('Done', 'Setting updated successfully');
        return redirect(route('editSetting',1));
    }

    public function contacts(){
        $title = trans('admin.contacts');
        $this->request_array = ['id'];
        $request_array = $this->request_array;
        foreach ($request_array as $item) $$item = request()->has($item) ? request()->$item : false;

        $contacts = Contact::orderBy('id', 'DESC');
        //apply filters
        $contacts->when($id, function ($query) use ($id) {
            $query->where('id','=', $id);
        });
        $search_query = request()->query();
        $contacts = $contacts->paginate(40);
        $total = $contacts->total();

        return view('generic::Admin.contact_admin_list', compact('contacts','title', 'total', 'search_query'));
    }

    public function contactDestroy($id)
    {
        $contact =Contact::withTrashed()->find($id);
        if($contact->trashed())
        {
            $contact->restore();
        }
        else
        {
            $contact->delete();
        }
        sweet_alert()->success('Done', 'Contact Deleted successfully');
        return redirect(route('listContact'));
    }
    private function prepare_inputs($inputs)
    {
        $input_file = 'logo_ar';
        if (request()->hasFile($input_file)) {
            $file = request()->file($input_file);
            $filename = rand(0, 20000) . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = base_path(Setting::$uploads_path);
            $upload_success = $file->move($destinationPath, $filename);
            if ($upload_success) {
                $inputs[$input_file] = $filename;
            }
        }else{
        unset($inputs[$input_file]);
        }

        $input_file = 'logo_en';
        if (request()->hasFile($input_file)) {
            $file = request()->file($input_file);
            $filename = rand(0, 20000) . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = base_path(Setting::$uploads_path);
            $upload_success = $file->move($destinationPath, $filename);
            if ($upload_success) {
                $inputs[$input_file] = $filename;
            }
        }else{
        unset($inputs[$input_file]);
        }
        $input_file = 'logo_white_ar';
        if (request()->hasFile($input_file)) {
            $file = request()->file($input_file);
            $filename = rand(0, 20000) . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = base_path(Setting::$uploads_path);
            $upload_success = $file->move($destinationPath, $filename);
            if ($upload_success) {
                $inputs[$input_file] = $filename;
            }
        }else{
        unset($inputs[$input_file]);
        }
        $input_file = 'logo_white_en';
        if (request()->hasFile($input_file)) {
            $file = request()->file($input_file);
            $filename = rand(0, 20000) . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = base_path(Setting::$uploads_path);
            $upload_success = $file->move($destinationPath, $filename);
            if ($upload_success) {
                $inputs[$input_file] = $filename;
            }
        }else{
        unset($inputs[$input_file]);
        }

        $inputs['meta_keywords_ar'] = implode('&', $inputs['meta_keywords_ar']);
        $inputs['meta_keywords_en'] = implode('&', $inputs['meta_keywords_en']);
        $inputs['about_ar'] = nl2br($inputs['about_ar'], false);
        $inputs['about_en'] = nl2br($inputs['about_en'], false);
        $inputs['terms_ar'] = nl2br($inputs['terms_ar'], false);
        $inputs['terms_en'] = nl2br($inputs['terms_en'], false);
        $inputs['policy_ar'] = nl2br($inputs['policy_ar'], false);
        $inputs['policy_en'] = nl2br($inputs['policy_en'], false);

        return $inputs;
    }


    public function whatsapp()
    {
        $title = 'Whatsapp';
        $max_users = 10;
        $max_messages = 10;
        $countries = Contact::select('country')->distinct()->get();
        return view('generic::Admin.whatsapp_admin_form', ['countries' => $countries, 'title'=>$title, 'max_users'=>$max_users, 'max_messages'=>$max_messages
            , 'consume_user_count' => $this->consume_user_count, 'consume_message_count' => $this->consume_message_count
        ]);
    }
    public function whatsappStore(Request $request)
    {
        $setting = $this->SettingRepository->first();
        $user_inputs = $this->prepare_inputs_wa($request->except(['_token']));
        $message = strip_tags($user_inputs['message']);
        $phones = explode(',', $user_inputs['phones']);
        $image_url = @asset($setting::$uploads_path_wa.$user_inputs['image']);
        $country_code = $user_inputs['country_code'];
        if(count($phones) > 0) {
            foreach ($phones as $phone) {
//                if(($this->consume_user_count < TypeConstants::WA_MAX_USER) && ($this->consume_message_count < TypeConstants::WA_MAX_MESSAGE)){
                $phone = $country_code.$phone;
                $wa = new WA();
                $wa->sendTextImageWithTemplate(trim($phone), 'gymmawy_hello_message',
                    [
                        [
                            "type" => "text",
                            "text" => "*".trans('admin.gymmawy_clients')."*"
                        ],
                        [
                            "type" => "text",
                            "text" => @$message
                        ],
                        [
                            "type" => "text",
                            "text" => "*".@$setting->phone."*"
                        ]
                    ], $image_url, $country_code ? false : true);

//                    $this->consume_user_count+=1;
//                    $this->consume_message_count+=1;
//                }
            }
            sweet_alert()->success(trans('admin.done'), trans('admin.successfully_send'));
        }else
            sweet_alert()->error('error', trans('global.unsuccessfully_send'));

        return redirect(route('createWhatsapp'));
    }
    public function phonesByAjax(){
        $phones = [];
        $type = request('type');
        $countries = request('countries_id');
        if($type == 2){
            $phones = Contact::where('type', 1)->when($countries, function ($q) use ($countries){$q->whereIn('country', $countries);})->pluck('phone')->toArray();
        }else if($type == 3){
            $phones = Contact::where('type', 2)->when($countries, function ($q) use ($countries){$q->whereIn('country', $countries);})->pluck('phone')->toArray();
        }else if($type == 4){
            $phones = Contact::where('type', 0)->when($countries, function ($q) use ($countries){$q->whereIn('country', $countries);})->pluck('phone')->toArray();
        }
        return implode(', ', $phones);
    }

    private function prepare_inputs_wa($inputs)
    {
        $input_file = 'image';
        if (request()->hasFile($input_file)) {
            $file = request()->file($input_file);
            $filename = rand(0, 20000) . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = base_path(Setting::$uploads_path_wa);

            $upload_success = Image::make($file)->resize(320, null, function ($constraint) {
                $constraint->aspectRatio(); //to preserve the aspect ratio
                $constraint->upsize();
            })->save($destinationPath.$filename);
//            $upload_success = $file->move($destinationPath, $filename);
            if ($upload_success) {
                $inputs[$input_file] = $filename;
            }
        } else {
            unset($inputs[$input_file]);
        }
        return $inputs;
    }
}

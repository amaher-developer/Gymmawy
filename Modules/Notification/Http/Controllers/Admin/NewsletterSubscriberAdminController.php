<?php

namespace Modules\Notification\Http\Controllers\Admin;

use Modules\Generic\Http\Controllers\Admin\GenericAdminController;
use Modules\Notification\Http\Requests\NewsletterSubscriberRequest;
use Modules\Notification\Models\NewsletterSubscriber;
use App\Http\Controllers\Controller;

class NewsletterSubscriberAdminController extends GenericAdminController
{
    public function index()
    {
        $title = trans('admin.newsletter_subscriber');
        if(request('trashed'))
        {
            $newslettersubscribers = NewsletterSubscriber::onlyTrashed()->paginate(50);
        }
        else
        {
            $newslettersubscribers = NewsletterSubscriber::paginate(50);
        }

        $total = $newslettersubscribers->total();

        return view('notification::Admin.newslettersubscriber_admin_list', compact('newslettersubscribers','total','title'));
    }

    public function create()
    {
        $title = 'Create NewsletterSubscriber';
        return view('notification::Admin.newslettersubscriber_admin_form', ['newslettersubscriber' => new NewsletterSubscriber(),'title'=>$title]);
    }

    public function store(NewsletterSubscriberRequest $request)
    {
        $newslettersubscriber_inputs = $this->prepare_inputs($request->except(['_token']));
        NewsletterSubscriber::create($newslettersubscriber_inputs);
        sweet_alert()->success('Done', 'NewsletterSubscriber Added successfully');
        return redirect(route('listNewsletterSubscriber'));
    }

    public function edit($id)
    {
        $newslettersubscriber =NewsletterSubscriber::withTrashed()->find($id);
        $title = 'Edit NewsletterSubscriber';
        return view('notification::Admin.newslettersubscriber_admin_form', ['newslettersubscriber' => $newslettersubscriber,'title'=>$title]);
    }

    public function update(NewsletterSubscriberRequest $request, $id)
    {
        $newslettersubscriber =NewsletterSubscriber::withTrashed()->find($id);
        $newslettersubscriber_inputs = $this->prepare_inputs($request->except(['_token']));
        $newslettersubscriber->update($newslettersubscriber_inputs);
        sweet_alert()->success('Done', 'NewsletterSubscriber updated successfully');
        return redirect(route('listNewsletterSubscriber'));
    }

    public function destroy($id)
      {
          $newslettersubscriber =NewsletterSubscriber::withTrashed()->find($id);
          if($newslettersubscriber->trashed())
          {
              $newslettersubscriber->restore();
          }
          else
          {
              $newslettersubscriber->delete();
          }
        sweet_alert()->success('Done', 'NewsletterSubscriber deleted successfully');
        return redirect(route('listNewsletterSubscriber'));
    }

    private function prepare_inputs($inputs)
    {
        $input_file = 'image';
        if (request()->hasFile($input_file)) {
            $file = request()->file($input_file);
            $filename = rand(0, 20000) . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = base_path(NewsletterSubscriber::$uploads_path);
            $upload_success = $file->move($destinationPath, $filename);
            if ($upload_success) {
                $inputs[$input_file] = $filename;
            }
        }
        !$inputs['deleted_at']?$inputs['deleted_at']=null:'';

        return $inputs;
    }

}

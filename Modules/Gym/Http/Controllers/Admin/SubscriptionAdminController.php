<?php

namespace Modules\Gym\Http\Controllers\Admin;

use Illuminate\Container\Container as Application;
use Modules\Generic\Http\Controllers\Admin\GenericAdminController;
use Modules\Gym\Http\Requests\GymSubscriptionRequest;
use Modules\Gym\Repositories\GymSubscriptionRepository;
use Modules\Gym\Models\GymSubscription;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class SubscriptionAdminController extends GenericAdminController
{
     public $SubscriptionRepository;

         public function __construct()
         {
             parent::__construct();

             $this->SubscriptionRepository=new GymSubscriptionRepository(new Application);
         }


    public function index()
    {

        $title = 'subscriptions List';
        $this->request_array = ['id'];
        $request_array = $this->request_array;
        foreach ($request_array as $item) $$item = request()->has($item) ? request()->$item : false;
        if(request('trashed'))
        {
            $subscriptions = $this->SubscriptionRepository->onlyTrashed()->orderBy('id', 'DESC');
        }
        else
        {
            $subscriptions = $this->SubscriptionRepository->orderBy('id', 'DESC');
        }


             //apply filters
                $subscriptions->when($id, function ($query) use ($id) {
                        $query->where('id','=', $id);
                });
                 $search_query = request()->query();

                       if (request()->ajax() && request()->exists('export')) {
                             $subscriptions = $subscriptions->get();
                             $array = $this->prepareForExport($subscriptions);
                             $fileName = 'subscriptions-' . Carbon::now()->toDateTimeString();
                             $file = Excel::create($fileName, function ($excel) use ($array) {
                                 $excel->setTitle('title');
                                 $excel->sheet('sheet1', function ($sheet) use ($array) {
                                     $sheet->fromArray($array);
                                 });
                             });
                             $file = $file->string('xlsx');
                             return [
                                 'name' => $fileName,
                                 'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($file)
                             ];
                         }
                         if ($this->limit) {
                             $subscriptions = $subscriptions->paginate($this->limit);
                             $total = $subscriptions->total();
                         } else {
                             $subscriptions = $subscriptions->get();
                             $total = $subscriptions->count();
                         }


        return view('gym::Admin.subscription_admin_list', compact('subscriptions','title', 'total', 'search_query'));
    }

    private function prepareForExport($data)
    {
        return array_map(function ($row) {
            return [
                'ID' => $row['id']
            ];
        }, $data->toArray());
    }

    public function create()
    {
        $title = 'Create Subscription';
        return view('gym::Admin.subscription_admin_form', ['subscription' => new GymSubscription(),'title'=>$title]);
    }

    public function store(GymSubscriptionRequest $request)
    {
        $subscription_inputs = $this->prepare_inputs($request->except(['_token']));
        $this->SubscriptionRepository->create($subscription_inputs);
        sweet_alert()->success('Done', 'Subscription Added successfully');
        return redirect(route('listSubscription'));
    }

    public function edit($id)
    {
        $subscription =$this->SubscriptionRepository->withTrashed()->find($id);
        $title = 'Edit Subscription';
        return view('gym::Admin.subscription_admin_form', ['subscription' => $subscription,'title'=>$title]);
    }

    public function update(GymSubscriptionRequest $request, $id)
    {
        $subscription =$this->SubscriptionRepository->withTrashed()->find($id);
        $subscription_inputs = $this->prepare_inputs($request->except(['_token']));
        $subscription->update($subscription_inputs);
        sweet_alert()->success('Done', 'Subscription Updated successfully');
        return redirect(route('listSubscription'));
    }

    public function destroy($id)
      {
          $subscription =$this->SubscriptionRepository->withTrashed()->find($id);
          if($subscription->trashed())
          {
              $subscription->restore();
          }
          else
          {
              $subscription->delete();
          }
        sweet_alert()->success('Done', 'Subscription Deleted successfully');
        return redirect(route('listSubscription'));
    }

    private function prepare_inputs($inputs)
    {
        $input_file = 'image';
        $uploaded='';

                $destinationPath = base_path($this->SubscriptionRepository->model()::$uploads_path);
                $ThumbnailsDestinationPath = base_path($this->SubscriptionRepository->model()::$thumbnails_uploads_path);
        
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, $mode = 0777, true, true);
                }
                if (!File::exists($ThumbnailsDestinationPath)) {
                    File::makeDirectory($ThumbnailsDestinationPath, $mode = 0777, true, true);
                }
                if (request()->hasFile($input_file)) {
                    $file = request()->file($input_file);
        
                    if (is_image($file->getRealPath())) {
                        $filename = rand(0, 20000) . time() . '.' . $file->getClientOriginalExtension();
        
        
                        $uploaded = $filename;
        
                        $img = Image::make($file);
                        $original_width = $img->width();
                        $original_height = $img->height();
        
                        if ($original_width > 1200 || $original_height > 900) {
                            if ($original_width < $original_height) {
                                $new_width = 1200;
                                $new_height = ceil($original_height * 900 / $original_width);
                            } else {
                                $new_height = 900;
                                $new_width = ceil($original_width * 1200 / $original_height);
                            }
        
                            //save used image
                            $img->encode('jpg', 90)->save($destinationPath . $filename);
                            $img->resize($new_width, $new_height, function ($constraint) {
                                $constraint->aspectRatio();
                            })->encode('jpg', 90)->save($destinationPath . '' . $filename);
        
                            //create thumbnail
                            if ($original_width < $original_height) {
                                $thumbnails_width = 400;
                                $thumbnails_height = ceil($new_height * 300 / $new_width);
                            } else {
                                $thumbnails_height = 300;
                                $thumbnails_width = ceil($new_width * 400 / $new_height);
                            }
                            $img->resize($thumbnails_width, $thumbnails_height, function ($constraint) {
                                $constraint->aspectRatio();
                            })->encode('jpg', 90)->save($ThumbnailsDestinationPath . '' . $filename);
                        } else {
                            //save used image
                            $img->encode('jpg', 90)->save($destinationPath . $filename);
                            //create thumbnail
                            if ($original_width < $original_height) {
                                $thumbnails_width = 400;
                                $thumbnails_height = ceil($original_height * 300 / $original_width);
                            } else {
                                $thumbnails_height = 300;
                                $thumbnails_width = ceil($original_width * 400 / $original_height);
                            }
                            $img->resize($thumbnails_width, $thumbnails_height, function ($constraint) {
                                $constraint->aspectRatio();
                            })->encode('jpg', 90)->save($ThumbnailsDestinationPath . '' . $filename);
                        }
                            $inputs[$input_file]=$uploaded;
                    }
        
                }
        

        !$inputs['deleted_at']?$inputs['deleted_at']=null:'';

        return $inputs;
    }

}

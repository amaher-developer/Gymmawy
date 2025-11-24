<?php

namespace Modules\Trainer\Http\Controllers\Admin;

use Modules\Client\Http\Requests\TrainingClientRequest;
use Modules\Client\Models\TrainingClient;
use Modules\Trainer\Http\Requests\TrainingPlanRequest;
use Modules\Trainer\Http\Requests\TrainingSubscriptionRequest;
use Modules\Trainer\Models\TrainingPlan;
use Modules\Trainer\Models\TrainingSubscription;
use Modules\Trainer\Repositories\TrainingPlanRepository;
use Modules\Trainer\Repositories\TrainingSubscriptionRepository;
use Illuminate\Container\Container as Application;
use Modules\Generic\Http\Controllers\Admin\GenericAdminController;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class TrainingPlanAdminController extends GenericAdminController
{
     public $PlanRepository;

         public function __construct()
         {
             parent::__construct();

             $this->PlanRepository=new TrainingPlanRepository(new Application);
         }


    public function index()
    {
        $title = 'training plans List';
        $this->request_array = ['id', 'date'];
        $request_array = $this->request_array;
        foreach ($request_array as $item) $$item = request()->has($item) ? request()->$item : false;
        if(request('trashed'))
        {
            $plans = $this->PlanRepository->onlyTrashed()->orderBy('id', 'DESC');
        }
        else
        {
            $plans = $this->PlanRepository->orderBy('id', 'DESC');
        }


             //apply filters
        $plans->when($id, function ($query) use ($id) {
                        $query->where('id','=', $id);
                });
        $plans->when($date, function ($query) use ($date) {
                        $query->where('created_at','=', $date);
                });
                 $search_query = request()->query();

                       if (request()->ajax() && request()->exists('export')) {
                           $plans = $plans->get();
                             $array = $this->prepareForExport($plans);
                             $fileName = 'training-subscriptions-' . Carbon::now()->toDateTimeString();
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
                             $plans = $plans->paginate($this->limit);
                             $total = $plans->total();
                         } else {
                             $plans = $plans->get();
                             $total = $plans->count();
                         }


        return view('trainer::Admin.training_plan_admin_list', compact('plans','title', 'total', 'search_query'));
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
        $title = 'Create Training Plan';
        return view('trainer::Admin.training_plan_admin_form', ['plan' => new TrainingPlan(),'title'=>$title]);
    }

    public function store(TrainingPlanRequest  $request)
    {
        $plan_inputs = $this->prepare_inputs($request->except(['_token']));
        $this->PlanRepository->create($plan_inputs);
        sweet_alert()->success('Done', 'Training Plan Added successfully');
        return redirect(route('listTrainingPlan'));
    }

    public function edit($id)
    {
        $plan =$this->PlanRepository->withTrashed()->find($id);
        $title = 'Edit Training Plan';
        return view('trainer::Admin.training_plan_admin_form', ['plan' => $plan,'title'=>$title]);
    }

    public function update(TrainingPlanRequest $request, $id)
    {
        $plan =$this->PlanRepository->withTrashed()->find($id);
        $plan_inputs = $this->prepare_inputs($request->except(['_token']));
        $plan->update($plan_inputs);
        sweet_alert()->success('Done', 'Training Plan Updated successfully');
        return redirect(route('listTrainingPlan'));
    }

    public function destroy( $id)
      {
          $client =$this->PlanRepository->withTrashed()->find($id);
          if($client->trashed())
          {
              $client->restore();
          }
          else
          {
              $client->delete();
          }
        sweet_alert()->success('Done', 'Training Plan Deleted successfully');
        return redirect(route('listTrainingPlan'));
    }

    private function prepare_inputs($inputs)
    {
        $input_file = 'image';
        $uploaded='';

                $destinationPath = base_path($this->PlanRepository->model()::$uploads_path);
                $ThumbnailsDestinationPath = base_path($this->PlanRepository->model()::$thumbnails_uploads_path);
        
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
        

//        !$inputs['deleted_at']?$inputs['deleted_at']=null:'';

        return $inputs;
    }

}

<?php

namespace Modules\Client\Http\Controllers\Admin;

use Modules\Client\Http\Requests\TrainingClientRequest;
use Modules\Client\Models\TrainingClient;
use Modules\Client\Repositories\TrainingClientRepository;
use Modules\Gym\Models\Gym;
use Illuminate\Container\Container as Application;
use Modules\Generic\Http\Controllers\Admin\GenericAdminController;
use Modules\Client\Http\Requests\ClientRequest;
use Modules\Client\Models\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class TrainingClientAdminController extends GenericAdminController
{
     public $ClientRepository;

         public function __construct()
         {
             parent::__construct();

             $this->ClientRepository=new TrainingClientRepository(new Application);
         }


    public function index()
    {

        $title = 'training clients List';
        $this->request_array = ['id', 'phone'];
        $request_array = $this->request_array;
        foreach ($request_array as $item) $$item = request()->has($item) ? request()->$item : false;
        if(request('trashed'))
        {
            $clients = $this->ClientRepository->onlyTrashed()->orderBy('id', 'DESC');
        }
        else
        {
            $clients = $this->ClientRepository->orderBy('id', 'DESC');
        }


             //apply filters
                $clients->when($id, function ($query) use ($id) {
                        $query->where('id','=', $id);
                });
                $clients->when($phone, function ($query) use ($phone) {
                        $query->where('phone','=', $phone);
                });
                 $search_query = request()->query();

                       if (request()->ajax() && request()->exists('export')) {
                             $clients = $clients->get();
                             $array = $this->prepareForExport($clients);
                             $fileName = 'training-clients-' . Carbon::now()->toDateTimeString();
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
                             $clients = $clients->paginate($this->limit);
                             $total = $clients->total();
                         } else {
                             $clients = $clients->get();
                             $total = $clients->count();
                         }


        return view('client::Admin.training_client_admin_list', compact('clients','title', 'total', 'search_query'));
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
        $code = rand(11111, 99999);
        $title = 'Create Training Client';
        return view('client::Admin.training_client_admin_form', ['client' => new TrainingClient(),'title'=>$title,'code'=>$code]);
    }

    public function store(TrainingClientRequest  $request)
    {
        $client_inputs = $this->prepare_inputs($request->except(['_token']));
        $this->ClientRepository->create($client_inputs);
        sweet_alert()->success('Done', 'Training Client Added successfully');
        return redirect(route('listTrainingClient'));
    }

    public function edit($id)
    {
        $code = rand(11111, 99999);
        $client =$this->ClientRepository->withTrashed()->find($id);
        $title = 'Edit Training Client';
        return view('client::Admin.training_client_admin_form', ['client' => $client,'title'=>$title,'code'=>$code]);
    }

    public function update(TrainingClientRequest $request, $id)
    {
        $client =$this->ClientRepository->withTrashed()->find($id);
        $client_inputs = $this->prepare_inputs($request->except(['_token']));
        $client->update($client_inputs);
        sweet_alert()->success('Done', 'Training Client Updated successfully');
        return redirect(route('listTrainingClient'));
    }

    public function destroy($id)
      {
          $client =$this->ClientRepository->withTrashed()->find($id);
          if($client->trashed())
          {
              $client->restore();
          }
          else
          {
              $client->delete();
          }
        sweet_alert()->success('Done', 'Training Client Deleted successfully');
        return redirect(route('listTrainingClient'));
    }

    private function prepare_inputs($inputs)
    {
        $input_file = 'image';
        $uploaded='';

                $destinationPath = base_path($this->ClientRepository->model()::$uploads_path);
                $ThumbnailsDestinationPath = base_path($this->ClientRepository->model()::$thumbnails_uploads_path);
        
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

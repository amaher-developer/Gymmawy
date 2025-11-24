<?php

namespace Modules\Generic\Http\Controllers\Admin;

use Illuminate\Container\Container as Application;
use Modules\Generic\Http\Controllers\Admin\GenericAdminController;
use Modules\Generic\Http\Requests\CityRequest;
use Modules\Generic\Repositories\CityRepository;
use Modules\Generic\Models\City;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class CityAdminController extends GenericAdminController
{
     public $CityRepository;

         public function __construct()
         {
             parent::__construct();

             $this->CityRepository=new CityRepository(new Application);
         }


    public function index()
    {

        $title = 'cities List';
        $this->request_array = ['id'];
        $request_array = $this->request_array;
        foreach ($request_array as $item) $$item = request()->has($item) ? request()->$item : false;
        if(request('trashed'))
        {
            $cities = $this->CityRepository->onlyTrashed()->orderBy('id', 'DESC');
        }
        else
        {
            $cities = $this->CityRepository->orderBy('id', 'DESC');
        }


             //apply filters
                $cities->when($id, function ($query) use ($id) {
                        $query->where('id','=', $id);
                });
                 $search_query = request()->query();

                       if (request()->ajax() && request()->exists('export')) {
                             $cities = $cities->get();
                             $array = $this->prepareForExport($cities);
                             $fileName = 'cities-' . Carbon::now()->toDateTimeString();
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
                             $cities = $cities->paginate($this->limit);
                             $total = $cities->total();
                         } else {
                             $cities = $cities->get();
                             $total = $cities->count();
                         }


        return view('generic::Admin.city_admin_list', compact('cities','title', 'total', 'search_query'));
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
        $title = 'Create City';
        return view('generic::Admin.city_admin_form', ['city' => new City(),'title'=>$title]);
    }

    public function store(CityRequest $request)
    {
        $city_inputs = $this->prepare_inputs($request->except(['_token']));
        $this->CityRepository->create($city_inputs);
        sweet_alert()->success('Done', 'City Added successfully');
        return redirect(route('listCity'));
    }

    public function edit($id)
    {
        $city =$this->CityRepository->withTrashed()->find($id);
        $title = 'Edit City';
        return view('generic::Admin.city_admin_form', ['city' => $city,'title'=>$title]);
    }

    public function update(CityRequest $request, $id)
    {
        $city =$this->CityRepository->withTrashed()->find($id);
        $city_inputs = $this->prepare_inputs($request->except(['_token']));
        $city->update($city_inputs);
        sweet_alert()->success('Done', 'City Updated successfully');
        return redirect(route('listCity'));
    }

    public function destroy($id)
      {
          $city =$this->CityRepository->withTrashed()->find($id);
          if($city->trashed())
          {
              $city->restore();
          }
          else
          {
              $city->delete();
          }
        sweet_alert()->success('Done', 'City Deleted successfully');
        return redirect(route('listCity'));
    }

    private function prepare_inputs($inputs)
    {
        $input_file = 'image';
        $uploaded='';

                $destinationPath = base_path($this->CityRepository->model()::$uploads_path);
                $ThumbnailsDestinationPath = base_path($this->CityRepository->model()::$thumbnails_uploads_path);
        
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

<?php

namespace Modules\Generic\Http\Controllers\Admin;

use Modules\Generic\Models\City;
use Illuminate\Container\Container as Application;
use Modules\Generic\Http\Controllers\Admin\GenericAdminController;
use Modules\Generic\Http\Requests\DistrictRequest;
use Modules\Generic\Repositories\DistrictRepository;
use Modules\Generic\Models\District;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class DistrictAdminController extends GenericAdminController
{
     public $DistrictRepository;

         public function __construct()
         {
             parent::__construct();

             $this->DistrictRepository=new DistrictRepository(new Application);
         }


    public function index()
    {

        $title = 'districts List';
        $this->request_array = ['id'];
        $request_array = $this->request_array;
        foreach ($request_array as $item) $$item = request()->has($item) ? request()->$item : false;
        if(request('trashed'))
        {
            $districts = $this->DistrictRepository->onlyTrashed()->orderBy('id', 'ASC');
        }
        else
        {
            $districts = $this->DistrictRepository->orderBy('id', 'ASC');
        }


             //apply filters
                $districts->when($id, function ($query) use ($id) {
                        $query->where('id','=', $id);
                });
                 $search_query = request()->query();

                       if (request()->ajax() && request()->exists('export')) {
                             $districts = $districts->get();
                             $array = $this->prepareForExport($districts);
                             $fileName = 'districts-' . Carbon::now()->toDateTimeString();
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
                             $districts = $districts->paginate($this->limit);
                             $total = $districts->total();
                         } else {
                             $districts = $districts->get();
                             $total = $districts->count();
                         }


        return view('generic::Admin.district_admin_list', compact('districts','title', 'total', 'search_query'));
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
        $title = 'Create District';
        $cities = City::get();
        return view('generic::Admin.district_admin_form', ['district' => new District(),'title'=>$title, 'cities' => $cities]);
    }

    public function store(DistrictRequest $request)
    {
        $district_inputs = $this->prepare_inputs($request->except(['_token']));
        $this->DistrictRepository->create($district_inputs);
        sweet_alert()->success('Done', 'District Added successfully');
        return redirect(route('listDistrict'));
    }

    public function edit($id)
    {
        $district =$this->DistrictRepository->with('city')->withTrashed()->find($id);
        $cities = City::get();
        $title = 'Edit District';
        return view('generic::Admin.district_admin_form', ['district' => $district,'title'=>$title, 'cities' => $cities]);
    }

    public function update(DistrictRequest $request, $id)
    {
        $district =$this->DistrictRepository->withTrashed()->find($id);
        $district_inputs = $this->prepare_inputs($request->except(['_token']));
        $district->update($district_inputs);
        sweet_alert()->success('Done', 'District Updated successfully');
        return redirect(route('listDistrict'));
    }

    public function destroy($id)
      {
          $district =$this->DistrictRepository->withTrashed()->find($id);
          if($district->trashed())
          {
              $district->restore();
          }
          else
          {
              $district->delete();
          }
        sweet_alert()->success('Done', 'District Deleted successfully');
        return redirect(route('listDistrict'));
    }

    private function prepare_inputs($inputs)
    {
        $input_file = 'image';
        $uploaded='';

                $destinationPath = base_path($this->DistrictRepository->model()::$uploads_path);
                $ThumbnailsDestinationPath = base_path($this->DistrictRepository->model()::$thumbnails_uploads_path);
        
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

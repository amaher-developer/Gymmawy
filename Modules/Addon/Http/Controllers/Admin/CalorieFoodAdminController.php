<?php

namespace Modules\Addon\Http\Controllers\Admin;

use Modules\Addon\Repositories\CalorieCategoryRepository;
use Illuminate\Container\Container as Application;
use Modules\Generic\Http\Controllers\Admin\GenericAdminController;
use Modules\Addon\Http\Requests\CalorieFoodRequest;
use Modules\Addon\Repositories\CalorieFoodRepository;
use Modules\Addon\Models\CalorieFood;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class CalorieFoodAdminController extends GenericAdminController
{
     public $CalorieFoodRepository;
     public $CalorieCategoryRepository;

         public function __construct()
         {
             parent::__construct();

             $this->CalorieFoodRepository=new CalorieFoodRepository(new Application);
         }


    public function index()
    {

        $title = 'caloriefoods List';
        $this->request_array = ['id'];
        $request_array = $this->request_array;
        foreach ($request_array as $item) $$item = request()->has($item) ? request()->$item : false;
        if(request('trashed'))
        {
            $caloriefoods = $this->CalorieFoodRepository->onlyTrashed()->orderBy('id', 'DESC');
        }
        else
        {
            $caloriefoods = $this->CalorieFoodRepository->orderBy('id', 'DESC');
        }


             //apply filters
                $caloriefoods->when($id, function ($query) use ($id) {
                        $query->where('id','=', $id);
                });
                 $search_query = request()->query();

                       if (request()->ajax() && request()->exists('export')) {
                             $caloriefoods = $caloriefoods->get();
                             $array = $this->prepareForExport($caloriefoods);
                             $fileName = 'caloriefoods-' . Carbon::now()->toDateTimeString();
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
                             $caloriefoods = $caloriefoods->paginate($this->limit);
                             $total = $caloriefoods->total();
                         } else {
                             $caloriefoods = $caloriefoods->get();
                             $total = $caloriefoods->count();
                         }


        return view('addon::Admin.caloriefood_admin_list', compact('caloriefoods','title', 'total', 'search_query'));
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
        $title = 'Create CalorieFood';
        $this->CalorieCategoryRepository = new CalorieCategoryRepository(new Application);
        return view('addon::Admin.caloriefood_admin_form', ['caloriefood' => new CalorieFood(), 'categories' => $this->CalorieCategoryRepository->get(),'title'=>$title]);
    }

    public function store(CalorieFoodRequest $request)
    {
        $caloriefood_inputs = $this->prepare_inputs($request->except(['_token']));
        $this->CalorieFoodRepository->create($caloriefood_inputs);
        sweet_alert()->success('Done', 'CalorieFood Added successfully');
        return redirect(route('createCalorieFood'));
    }

    public function edit($id)
    {
        $this->CalorieCategoryRepository = new CalorieCategoryRepository(new Application);
        $caloriefood =$this->CalorieFoodRepository->withTrashed()->find($id);
        $title = 'Edit CalorieFood';
        return view('addon::Admin.caloriefood_admin_form', ['categories' => $this->CalorieCategoryRepository->get(), 'caloriefood' => $caloriefood,'title'=>$title]);
    }

    public function update(CalorieFoodRequest $request, $id)
    {
        $caloriefood =$this->CalorieFoodRepository->withTrashed()->find($id);
        $caloriefood_inputs = $this->prepare_inputs($request->except(['_token']));
        $caloriefood->update($caloriefood_inputs);
        sweet_alert()->success('Done', 'CalorieFood Updated successfully');
        return redirect(route('listCalorieFood'));
    }

    public function destroy($id)
      {
          $caloriefood =$this->CalorieFoodRepository->withTrashed()->find($id);
          if($caloriefood->trashed())
          {
              $caloriefood->restore();
          }
          else
          {
              $caloriefood->delete();
          }
        sweet_alert()->success('Done', 'CalorieFood Deleted successfully');
        return redirect(route('listCalorieFood'));
    }

    private function prepare_inputs($inputs)
    {
        $input_file = 'image';
        $uploaded='';

                $destinationPath = base_path($this->CalorieFoodRepository->model()::$uploads_path);
                $ThumbnailsDestinationPath = base_path($this->CalorieFoodRepository->model()::$thumbnails_uploads_path);
        
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

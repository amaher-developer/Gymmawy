<?php

namespace Modules\Trainer\Http\Controllers\Admin;

use Modules\Generic\Models\City;
use Modules\Generic\Models\District;
use Modules\Gym\Models\Category;
use Illuminate\Container\Container as Application;
use Modules\Generic\Http\Controllers\Admin\GenericAdminController;
use Modules\Trainer\Http\Requests\TrainerRequest;
use Modules\Trainer\Repositories\TrainerRepository;
use Modules\Trainer\Models\Trainer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class TrainerAdminController extends GenericAdminController
{
    public $TrainerRepository;

    public function __construct()
    {
        parent::__construct();

        $this->TrainerRepository = new TrainerRepository(new Application);
    }


    public function index()
    {

        $title = 'trainers List';
        $this->request_array = ['id', 'published', 'order_by'];
        $request_array = $this->request_array;
        foreach ($request_array as $item) $$item = request()->has($item) ? request()->$item : false;
        if (request('trashed')) {
            $trainers = $this->TrainerRepository->onlyTrashed();
        } else {
            $trainers = $this->TrainerRepository;
        }


        //apply filters
        $trainers->when($id, function ($query) use ($id) {
            $query->where('id', '=', $id);
        });

        $trainers->when(isset($published) && ($published != ''), function ($query) use ($published) {
            $query->where('published', '=', $published);
        });
        $search_query = request()->query();

        $trainers->when(isset($order_by) && ($order_by != ''), function ($query) use ($order_by) {
            if($order_by == 'views'){
                $query->orderBy('views', 'desc');
            }else if($order_by == 'date'){
                $query->orderBy('created_at', 'desc');
            }
        });
        if (request()->ajax() && request()->exists('export')) {
            $trainers = $trainers->get();
            $array = $this->prepareForExport($trainers);
            $fileName = 'trainers-' . Carbon::now()->toDateTimeString();
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
            $trainers = $trainers->orderBy('id', 'DESC')->paginate($this->limit);
            $total = $trainers->total();
        } else {
            $trainers = $trainers->orderBy('id', 'DESC')->get();
            $total = $trainers->count();
        }


        return view('trainer::Admin.trainer_admin_list', compact('trainers', 'title', 'total', 'search_query'));
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
        $title = 'Create Trainer';
        return view('trainer::Admin.trainer_admin_form', ['trainer' => new Trainer(), 'title' => $title]);
    }

    public function store(TrainerRequest $request)
    {
        $trainer_inputs = $this->prepare_inputs($request->except(['_token']));
        $trainer_inputs['user_id'] = Auth::user()->id;
        $this->TrainerRepository->create($trainer_inputs);
        sweet_alert()->success('Done', 'Trainer Added successfully');
        return redirect(route('listTrainer'));
    }

    public function edit($id)
    {
        $trainer = $this->TrainerRepository->with(['districts', 'categories'])->withTrashed()->find($id);

        $trainer_category_ids = [];
        $trainer_district_ids = [];
        if ($trainer)
            $trainer_district_ids = $trainer->districts->pluck('id')->toArray();
        if ($trainer)
            $trainer_category_ids = $trainer->categories->pluck('id')->toArray();

        $cities = City::get();
        $districts = District::get();
        $categories = Category::get();


        $title = 'Edit Trainer';
        return view('trainer::Admin.trainer_admin_form', ['trainer' => $trainer, 'trainer_district_ids' => (array)$trainer_district_ids, 'trainer_category_ids' => (array)$trainer_category_ids, 'cities' => $cities, 'districts' => $districts, 'categories' => $categories, 'title' => $title]);
    }

    public function update(TrainerRequest $request, $id)
    {

        $trainer = $this->TrainerRepository->withTrashed()->find($id);
        $trainer_inputs = $this->prepare_inputs($request->except(['_token', 'districts', 'categories']));

        $city = City::with(['district'])->where('id', $request->city_id)->first();
        $districts = District::where('city_id', $request->city_id)->pluck('id')->toArray();
//        $districtAv = $city->district->pluck('id') ? $city->district->pluck('id')->toArray() : [];
//        $districts = array_values(array_intersect($request->districts, $districtAv));
        $categories = $request->categories;
        $trainer->update($trainer_inputs);

        $trainer->districts()->sync($districts);
        $trainer->categories()->sync($categories);

        sweet_alert()->success('Done', 'Trainer Updated successfully');
        return redirect(route('listTrainer'));
    }

    public function destroy($id)
    {
        $trainer = $this->TrainerRepository->withTrashed()->find($id);
        if ($trainer->trashed()) {
            $trainer->restore();
        } else {
            $trainer->delete();
        }
        sweet_alert()->success('Done', 'Trainer Deleted successfully');
        return redirect(route('listTrainer'));
    }


    private function prepare_inputs($inputs)
    {
        $input_file = 'image';
        $uploaded = '';

        $destinationPath = base_path(Trainer::$uploads_path);
        $ThumbnailsDestinationPath = base_path(Trainer::$thumbnails_uploads_path);
        $waterMarkUrl = base_path('resources/assets/front/img/watermark.png');

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
                    })->encode('jpg', 90);
                    $img->insert($waterMarkUrl, 'bottom-left', 5, 5);
                    $img->save($destinationPath . '' . $filename);

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
                    $img->encode('jpg', 90);
                    $img->insert($waterMarkUrl, 'bottom-left', 5, 5);
                    $img->save($destinationPath . $filename);
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
                $inputs[$input_file] = $uploaded;
            }

        }


//        !$inputs['deleted_at']?$inputs['deleted_at']=null:'';
        $inputs['published'] = $inputs['published'] ?? 0;

        return $inputs;
    }

}

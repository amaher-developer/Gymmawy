<?php

namespace Modules\Addon\Http\Controllers\Admin;

use Modules\Addon\Models\BodybuilderCompetition;
use Modules\Generic\Models\City;
use Modules\Generic\Models\Country;
use Modules\Generic\Models\District;
use Illuminate\Container\Container as Application;
use Modules\Generic\Http\Controllers\Admin\GenericAdminController;
use Modules\Addon\Http\Requests\BodybuilderRequest;
use Modules\Addon\Repositories\BodybuilderRepository;
use Modules\Addon\Models\Bodybuilder;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class BodybuilderAdminController extends GenericAdminController
{
    public $BodybuilderRepository;

    public function __construct()
    {
        parent::__construct();

        $this->BodybuilderRepository = new BodybuilderRepository(new Application);
    }


    public function index()
    {

        $title = 'bodybuilders List';
        $this->request_array = ['id'];
        $request_array = $this->request_array;
        foreach ($request_array as $item) $$item = request()->has($item) ? request()->$item : false;
        if (request('trashed')) {
            $bodybuilders = $this->BodybuilderRepository->onlyTrashed()->orderBy('id', 'DESC');
        } else {
            $bodybuilders = $this->BodybuilderRepository->orderBy('id', 'DESC');
        }

        //apply filters
        $bodybuilders->when($id, function ($query) use ($id) {
            $query->where('id', '=', $id);
        });
        $search_query = request()->query();

        if (request()->ajax() && request()->exists('export')) {
            $bodybuilders = $bodybuilders->get();
            $array = $this->prepareForExport($bodybuilders);
            $fileName = 'bodybuilders-' . Carbon::now()->toDateTimeString();
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
            $bodybuilders = $bodybuilders->paginate($this->limit);
            $total = $bodybuilders->total();
        } else {
            $bodybuilders = $bodybuilders->get();
            $total = $bodybuilders->count();
        }


        return view('addon::Admin.bodybuilder_admin_list', compact('bodybuilders', 'title', 'total', 'search_query'));
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
        $title = 'Create Bodybuilder';

        $countries = Country::get();
        $cities = City::get();
        $districts = District::get();
        return view('addon::Admin.bodybuilder_admin_form', [
            'bodybuilder' => new Bodybuilder(),
            'countries' => $countries,
            'cities' => $cities,
            'districts' => $districts,
            'title' => $title]);
    }

    public function store(BodybuilderRequest $request)
    {
        $bodybuilder_inputs = $this->prepare_inputs($request->except(['_token', 'competition', 'city_id']));
        $bodybuilder_inputs['description_en'] = nl2br($bodybuilder_inputs['description_en']);
        $bodybuilder_inputs['description_ar'] = nl2br($bodybuilder_inputs['description_ar']);
        $bodybuilder = $this->BodybuilderRepository->create($bodybuilder_inputs);

        $competition = $request->competition;
        if(@$competition){
            for ($i = 0; $i < count($competition['name_en']); $i++){
                if($competition['name_ar'][$i] && $competition['name_en'][$i])
                    $data[] = ['bodybuilder_id' => $bodybuilder->id,'name_ar' => $competition['name_ar'][$i],'name_en' => $competition['name_en'][$i],'year' => ($competition['year'][$i])];
            }

            BodybuilderCompetition::where('bodybuilder_id', $bodybuilder->id)->forceDelete();
            BodybuilderCompetition::insert($data);
        }


        sweet_alert()->success('Done', 'Bodybuilder Added successfully');
        return redirect(route('listBodybuilder'));
    }

    public function edit($id)
    {
        $bodybuilder = $this->BodybuilderRepository->withTrashed()->find($id);
        $bodybuilder_competitions = BodybuilderCompetition::where('bodybuilder_id', $id)->orderBy('year', 'asc')->get();
        $title = 'Edit Bodybuilder';

        $countries = Country::get();
        $cities = City::get();
        $districts = District::get();

        return view('addon::Admin.bodybuilder_admin_form', ['bodybuilder' => $bodybuilder,
            'bodybuilder_competitions' => $bodybuilder_competitions,
            'countries' => $countries,
            'cities' => $cities,
            'districts' => $districts,
            'title' => $title]);
    }

    public function update(BodybuilderRequest $request, $id)
    {
        $bodybuilder = Bodybuilder::withTrashed()->find($id);
        $bodybuilder_inputs = $this->prepare_inputs($request->except(['_token', 'competition', 'city_id']));
        $bodybuilder_inputs['description_en'] = nl2br($bodybuilder_inputs['description_en']);
        $bodybuilder_inputs['description_ar'] = nl2br($bodybuilder_inputs['description_ar']);

        $bodybuilder->update($bodybuilder_inputs);
        $competition = $request->competition;
        if(@$competition){
            for ($i = 0; $i < count($competition['name_en']); $i++){
                $data[] = ['bodybuilder_id' => $id,'name_ar' => $competition['name_ar'][$i],'name_en' => $competition['name_en'][$i],'year' => ($competition['year'][$i])];
            }

            BodybuilderCompetition::where('bodybuilder_id', $id)->forceDelete();
            BodybuilderCompetition::insert($data);
        }


        sweet_alert()->success('Done', 'Bodybuilder Updated successfully');
        return redirect(route('listBodybuilder'));
    }

    public function destroy($id)
    {
        $bodybuilder = $this->BodybuilderRepository->withTrashed()->find($id);
        if ($bodybuilder->trashed()) {
            $bodybuilder->restore();
        } else {
            $bodybuilder->delete();
        }
        sweet_alert()->success('Done', 'Bodybuilder Deleted successfully');
        return redirect(route('listBodybuilder'));
    }

    private function prepare_inputs($inputs)
    {
        $input_file = 'image';
        if (request()->hasFile($input_file)) {
            $inputs = $this->uploadFile($inputs, $input_file);
        } else {
            unset($inputs[$input_file]);
        }

        $input_file = 'cover_image';
        if (request()->hasFile($input_file)) {
            $inputs = $this->uploadFile($inputs, $input_file);
        } else {
            unset($inputs[$input_file]);
        }
        return $inputs;
    }

    private function uploadFile($inputs, $file)
    {
        $input_file = $file;
        $uploaded = '';

        $destinationPath = base_path($this->BodybuilderRepository->model()::$uploads_path);
        $ThumbnailsDestinationPath = base_path($this->BodybuilderRepository->model()::$thumbnails_uploads_path);
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
                    $img->encode('jpg', 90);
                    $img->insert($waterMarkUrl, 'bottom-left', 5, 5);
                    $img->resize($new_width, $new_height, function ($constraint) {
                        $constraint->aspectRatio();
                    })->encode('jpg', 90);
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

        return $inputs;
    }

}

<?php

namespace Modules\Trainer\Http\Controllers\Front;

use Modules\Article\Models\Article;
use Modules\Generic\Http\Controllers\Front\GenericFrontController;
use Modules\Generic\Models\City;
use Modules\Generic\Models\District;
use Modules\Gym\Models\Category;
use Modules\Gym\Models\Service;
use Modules\Trainer\Models\DistrictTrainer;
use Modules\Trainer\Repositories\TrainerRepository;


use Illuminate\Container\Container as Application;
use Modules\Trainer\Http\Requests\TrainerRequest;
use Modules\Trainer\Models\Trainer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\Request;


class TrainerFrontController extends GenericFrontController
{
    public $TrainerRepository;
    public $limit;

    public function __construct()
    {
        parent::__construct();

        $this->TrainerRepository = new TrainerRepository(new Application);

        $this->limit = 15;
    }

    public function show()
    {

        $trainer = $this->TrainerRepository->where('user_id', Auth::user()->id)->withTrashed()->first();
        $title = trans('admin.trainer_show');
        if(!($trainer))
            return redirect()->route('editUserTrainer');
        return view('trainer::Front.user.trainer_front_view', ['trainer' => $trainer, 'title' => $title]);
    }

    public function trainer($id, $slug)
    {
        $trainer = Trainer::active()->with(['user', 'districts', 'categories'])->find($id);
        if (!$trainer)
            return view('generic::Front.pages.404');
        $title = $trainer->name;
        $trainer->increment('views', 1);
        $articles = Article::with(['user'])->where('language', $this->lang)->orderByRaw('RAND()')->limit(3)->get();

        $metaKeywords = '';
        if ($trainer->categories->pluck('name')) $metaKeywords .= ', ' . implode(', ', $trainer->categories->pluck('name')->toArray());
//        if ($trainer->districts->pluck('name')) $metaKeywords .= ', ' . implode(', ', $trainer->districts->pluck('name')->toArray());
        ltrim($metaKeywords, ', ');

        $metaDescription = $trainer->name . ', ' . $trainer->about;
        $metaImage = $trainer->image;

        return view('trainer::Front.trainer', compact('trainer', 'metaImage','articles', 'title', 'metaKeywords', 'metaDescription'));
    }

    public function trainers()
    {
        $title = trans('admin.trainers');
        return view('trainer::Front.trainers', [ 'title' => $title]);
    }

    public function edit()
    {
        $trainer = $this->TrainerRepository->with(['districts', 'categories'])->where('user_id', Auth::user()->id)->withTrashed()->first();
        $trainer_category_ids = [];
//        $trainer_district_ids = [];
//        if ($trainer)
//            $trainer_district_ids = $trainer->districts->pluck('id')->toArray();
        if ($trainer)
            $trainer_category_ids = $trainer->categories->pluck('id')->toArray();

        $this->user->is_trainer ? $title = trans('admin.trainer_edit') : $title = trans('admin.trainer_add');
//        $cities = City::get();
//        $districts = District::get();
        $categories = Category::get();
        return view('trainer::Front.user.trainer_front_form', ['trainer' => $trainer, 'trainer_category_ids' => (array)$trainer_category_ids, 'categories' => $categories, 'title' => $title]);
    }

    public function update(TrainerRequest $request)
    {
        $trainer = $this->TrainerRepository->where('user_id', Auth::user()->id)->withTrashed()->first();
        $trainer_inputs = $this->prepare_inputs($request->except(['_token', 'districts', 'categories']));
        $trainer_inputs['user_id'] = Auth::user()->id;
//        $city = City::with(['district'])->where('id', $request->city_id)->first();
//        $districtAv = $city->district->pluck('id') ? $city->district->pluck('id')->toArray() : [];
//        $districts = array_values(array_intersect($request->districts, $districtAv));
        $categories = $request->categories;

        if ($trainer) {
            $trainer->update($trainer_inputs);
//            $trainer->districts()->sync($districts);
            $trainer->categories()->sync($categories);
        } else {
            $trainer_inputs['name_en'] = $trainer_inputs['name_ar'] = $trainer_inputs['name_' . $this->lang];
            $trainer = Trainer::create($trainer_inputs);
//            $trainer->districts()->attach($districts);
            $trainer->categories()->attach($categories);
        }

        $this->user->is_trainer = 1;

        sweet_alert()->success(trans('admin.done'), trans('admin.successfully_edited'));
        return redirect(route('showUserTrainer'));
    }


    public function search(Request $request)
    {

        $order = $request->order;

        $records = Trainer::active()->with(['categories']);

        if ($order == 'alphabet')
            $records->orderBy('name_' . $this->lang, 'ASC');
        elseif ($order == 'oldest')
            $records->orderBy('id', 'ASC');
        else
            $records->orderBy('id', 'DESC');


        $trainers = $records->offset(0)->limit($this->limit)->get();

        $title = trans('global.search_trainers');
        $categories = Category::get();

        $metaKeywords = ', '.implode(', ', $categories->pluck('name')->toArray());


        return view('trainer::Front.trainers',
            compact('title', 'trainers', 'categories', 'metaKeywords')
            , [
                'checked_categories' => (array)$categories,
            ]
        );
    }

    public function searchByAjax(Request $request)
    {
//        $city_id = $request->city_id;
//        $district_id = $request->district_id;
        $keyword = trim(strip_tags($request->keyword));
//        $services = explode(',', trim(strip_tags($request->services)));
        $categories = array_filter((array)$request->categories);
        $order = explode(',', trim(strip_tags($request->order)));
        $pager = intval($request->pager) + 1;
        if(array_filter($request->except(['_token', 'order'])) && !isset($request->pager)) $pager = 0;

        $trainers = Trainer::active()->with(['city',  'categories'])
         ->when($categories, function ($query) use ($categories) {
            $query->whereHas('categories', function ($q) use ($categories) {
                $q->whereIn('category_id', $categories);
            });
        })
            ->when($keyword, function ($query) use ($keyword) {
                $keywords = explode(' ', $keyword);
                $query->where('name_' . $this->lang, 'like', '%' . $keyword . '%');
                $query->orWhere('about_' . $this->lang, 'like', '%' . $keyword . '%');
                if (count($keywords) > 1) {
                    foreach ($keywords as $keyword) {
                        $query->orWhere('name_' . $this->lang, 'like', '%' . $keyword . '%');
                        $query->orWhere('about_' . $this->lang, 'like', '%' . $keyword . '%');
                    }
                }
            });

        $trainers = $trainers->offset((int)$pager * $this->limit)->limit($this->limit);

        if ($order == 'alphabet')
            $trainers->orderBy('name_' . $this->lang, 'ASC');
        elseif ($order == 'oldest')
            $trainers->orderBy('id', 'ASC');
        else
            $trainers->orderBy('id', 'DESC');
        $lastPage = false;
        if ($trainers->count() < $this->limit) $lastPage = true;
        $trainers = $trainers->get();

        $currentSearch = view('trainer::Front.ajax_trainers_render', [
            'trainers' => $trainers,
            'pager' => $pager,
//            'city_id' => $city_id,
            'lastPage' => $lastPage,
        ]);
        return $currentSearch;
    }

    public function getTrainerPhoneByAjax()
    {
        $trainerId = \request()->get('trainer_id');
        $phone = $this->TrainerRepository->where('id', $trainerId)->first();
        return $phone['phone'];

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

        return $inputs;
    }
}

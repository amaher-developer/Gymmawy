<?php

namespace Modules\Gym\Http\Controllers\Front;

use Modules\Article\Models\Article;
use Modules\Generic\Http\Controllers\Front\GenericFrontController;
use Modules\Generic\Models\City;
use Modules\Gym\Http\Requests\GymBrandRequest;
use Modules\Gym\Http\Requests\GymRequest;
use Modules\Gym\Models\Category;
use Modules\Gym\Models\GymBrand;
use Modules\Gym\Models\GymImage;
use Modules\Gym\Models\Gym;
use Modules\Gym\Models\Service;
use Modules\Gym\Repositories\GymRepository;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;


class GymFrontController extends GenericFrontController
{
    public $GymRepository;
    public $limit;
    public $imageName;

    public function __construct()
    {
        parent::__construct();
        $this->GymRepository = new GymRepository(new Application);
        $this->limit = 15;
    }

    public function gyms(Request $request)
    {
        $order = $request->order;

        $records = Gym::active()->with(['categories', 'gym_brand', 'district.city', 'favorites']);

        /* if ($order == 'alphabet')
            $records->orderBy('name_' . app()->getLocale(), 'ASC');
        else*/ if ($order == 'oldest')
            $records->orderBy('id', 'ASC');
        else
            $records->orderBy('id', 'DESC');

        $records = $records->offset(0)->limit($this->limit)->get();

        $categories = Category::get();
        $services = Service::get();

        $title = trans('global.search_gyms');

        $metaKeywords = ', ' . implode(', ', $categories->pluck('name')->toArray());
        $metaKeywords .= implode(', ', $services->pluck('name')->toArray());


        return view('gym::Front.gyms',
            compact('title', 'records', 'categories', 'services', 'metaKeywords')
            , [
                'checked_services' => (array)$services,
                'checked_categories' => (array)$categories,
            ]);
    }

    public function searchByAjax(Request $request)
    {
        $city_id = $request->city_id;
        $district_id = $request->district_id;
        $keyword = trim(strip_tags($request->keyword));
        $services = array_filter((array)$request->services);
        $categories = array_filter((array)$request->categories);
        $order = explode(',', trim(strip_tags($request->order)));
        $pager = intval($request->pager) + 1;

        if (array_filter($request->except(['_token', 'order'])) && !isset($request->pager)) $pager = 0;

        $gyms = Gym::active()->with(['categories', 'gym_brand', 'district.city', 'favorites']);
        $gyms->when($city_id, function ($query) use ($city_id) {
            $query->whereHas('district.city', function ($q) use ($city_id) {
                $q->where('city_id', $city_id);
            });
        })->when($district_id, function ($query) use ($district_id) {
            $query->whereHas('district', function ($q) use ($district_id) {
                $q->where('district_id', $district_id);
            });
        })->when($categories, function ($query) use ($categories) {
            $query->whereHas('categories', function ($q) use ($categories) {
                $q->whereIn('category_id', $categories);
            });
        })->when($services, function ($query) use ($services) {
            $query->whereHas('services', function ($q) use ($services) {
                $q->whereIn('service_id', $services);
            });
        })
            ->when($keyword, function ($query) use ($keyword) {
                $query->whereHas('gym_brand', function ($q) use ($keyword) {
                    $keywords = explode(' ', $keyword);
                    $q->where('name_' . $this->lang, 'like', '%' . $keyword . '%');
                    $q->orWhere('description_' . $this->lang, 'like', '%' . $keyword . '%');
                    if (count($keywords) > 1) {
                        foreach ($keywords as $keyword) {
                            $q->orWhere('name_' . $this->lang, 'like', '%' . $keyword . '%');
                            $q->orWhere('description_' . $this->lang, 'like', '%' . $keyword . '%');
                        }
                    }
                });

            });
        $gyms = $gyms->offset((int)$pager * $this->limit)->limit($this->limit);

        /* if ($order == 'alphabet')
            $gyms->orderBy('name_' . $this->lang, 'ASC');
        else */if ($order == 'oldest')
            $gyms->orderBy('id', 'ASC');
        else
            $gyms->orderBy('id', 'DESC');
        $lastPage = false;
        if ($gyms->count() < $this->limit) $lastPage = true;

        $gyms = $gyms->get();
//        $currentSearch = view('item::Front.ajax_search_render', compact('filters', 'item_types', 'deactivation_reasons','item_status','owner_types','finishes','cities', 'districts', 'items', 'title', 'saveSearchList', 'search_query', 'phrase'))->render();

        $currentSearch = view('gym::Front.ajax_search_render', [
            'records' => $gyms,
            'pager' => $pager,
            'lastPage' => $lastPage,
        ]);
        return $currentSearch;
    }


    public function gym($id, $slug)
    {
        $gym = Gym::active()->with(['services', 'categories', 'gym_brand', 'district.city', 'images', 'favorites'])->where('id', $id)->first();
        if (!$gym)
            return view('generic::Front.pages.404');

        $title = $gym->name;
        $gym->increment('views', 1);

        $category_ids = $gym->categories->pluck('id')->toArray();
        $articles = Article::with(['user'])->where('language', $this->lang)->orderByRaw('RAND()')->limit(3)->get();
        $related_gyms = Gym::with(['gym_brand', 'district.city'])
            ->where('id', '!=', $gym->id)
            ->where('district_id', $gym->district_id)
            ->whereHas('categories', function ($q) use ($category_ids){
                $q->whereIn('category_id', $category_ids);
            })
            ->orderByRaw('RAND()')->limit(3)->get();

        $metaKeywords = '';
//        foreach ($gym->branches as $key => $branch)
//            $districtNames[$key] = ($branch->district->name);

        if ($gym->categories->pluck('name')) $metaKeywords .= ', ' . implode(', ', $gym->categories->pluck('name')->toArray());
        if ($gym->services->pluck('name')) $metaKeywords .= ', ' . implode(', ', $gym->services->pluck('name')->toArray());
//        if(@$districtNames) $metaKeywords .= ', '.implode(', ', $districtNames);
        ltrim($metaKeywords, ', ');

        $metaDescription = $gym->name . ', ' . $gym->description;
        $metaImage = $gym->image;

        return view('gym::Front.gym', compact('gym', 'related_gyms','metaImage', 'title', 'metaKeywords', 'metaDescription', 'articles'));

    }

    public function create()
    {
        $title = trans('admin.location_add');
        $gym_category_ids = $gym_service_ids = $images = [];

        $categories = Category::get();
        $services = Service::get();
        return view('gym::Front.user.gymbranch_front_form',
            [
                'gym' => new Gym(),
                'categories' => $categories,
                'services' => $services,
                'title' => $title,
                'gym_service_ids' => (array)$gym_service_ids,
                'gym_category_ids' => (array)$gym_category_ids,
            ]);
    }

    public function store(GymRequest $request)
    {
        $gym_inputs = $this->prepare_inputs($request->except(['_token', 'images', 'city_id', 'categories', 'services']));
        $gym_inputs['gym_brand_id'] = Auth::user()->gym->id;
        if ($gym_inputs['phones']) $gym_inputs['phones'] = explode(',', $gym_inputs['phones']);
        $images = [];
        if ($request->images) $images = explode(',', trim($request->images, ','));

        $gym = Gym::create($gym_inputs);
        if (count((array)$request->categories) > 0) $gym->categories()->sync($request->categories);
        if (count((array)$request->services) > 0) $gym->services()->sync($request->services);

        if (count($images) > 0) {
            foreach ($images as $image)
                GymImage::updateOrCreate(['gym_id' => $gym->id, 'image' => $image], ['gym_id' => $gym->id, 'image' => $image]);
        }

        sweet_alert()->success(trans('admin.done'), trans('admin.successfully_added'));
        return redirect(route('showUserGymBrand'));
    }

    public function edit($id)
    {
        $gym = $this->GymRepository->with(['services', 'categories', 'images'])->where('id', $id)->where('gym_brand_id', Auth::user()->gym->id)->withTrashed()->first();
        $gym_category_ids = $gym_service_ids = $images = [];
        if ($gym) {
            $gym_category_ids = $gym->categories->pluck('id')->toArray();
            $gym_service_ids = $gym->services->pluck('id')->toArray();
            $images = $gym->images->pluck('image');
        }
        $title = trans('admin.location_edit');
        $cities = $this->cities;
        $categories = Category::get();
        $services = Service::get();

        $getImages = '';
        if ($images)
            $getImages = $this->getFileInfo($images);

        return view('gym::Front.user.gymbranch_front_form', [
            'gym' => $gym,
            'getImages' => $getImages,
            'gym_service_ids' => (array)$gym_service_ids,
            'gym_category_ids' => (array)$gym_category_ids,
            'cities' => $cities,
            'services' => $services,
            'categories' => $categories,
            'title' => $title
        ]);
    }


    public function update(GymRequest $request, $id)
    {
        $gym = $this->GymRepository->where('id', $id)->where('gym_brand_id', Auth::user()->gym->id)->withTrashed()->first();
        $gym_inputs = $this->prepare_inputs($request->except(['_token', 'images', 'city_id', 'categories', 'services']));
        $gym_inputs['gym_brand_id'] = Auth::user()->gym->id;
        if ($gym_inputs['phones']) $gym_inputs['phones'] = explode(',', $gym_inputs['phones']);
        $images = [];
        if ($request->images) $images = explode(',', trim($request->images, ','));
        if ($gym) {
            $gym->update($gym_inputs);
            if (count((array)$request->categories) > 0) $gym->categories()->sync($request->categories);
            if (count((array)$request->services) > 0) $gym->services()->sync($request->services);
        } else {
            $gym = Gym::create($gym_inputs);
//            if (count($images) > 0) $gym->images()->sync($images);
            if (count((array)$request->categories) > 0) $gym->categories()->sync($request->categories);
            if (count((array)$request->services) > 0) $gym->services()->sync($request->services);
        }

        if (count($images) > 0) {
            $oldImages = GymImage::whereNotIn('image', $images)->where('gym_id', $gym->id)->get();
            foreach ($oldImages as $oldImage) {
                unlink(GymImage::$uploads_path . $oldImage['original_image']);
                unlink(GymImage::$thumbnails_uploads_path . $oldImage['original_image']);
                GymImage::where('id', $oldImage['id'])->delete();
            }

            foreach ($images as $image)
                GymImage::updateOrCreate(['gym_id' => $gym->id, 'image' => $image], ['gym_id' => $gym->id, 'image' => $image]);
        }

        $this->user->is_gym = 1;
        sweet_alert()->success(trans('admin.done'), trans('admin.successfully_edited'));
        return redirect(route('showUserGymBrand'));
    }

    public function getGymPhoneByAjax()
    {
        $gymId = \request()->get('gym_id');
        $phone = GymBrand::where('id', $gymId)->first();
        return $phone['main_phone'];

    }

    public function getGymLocationPhoneByAjax()
    {
        $gymId = \request()->get('gym_id');
        $id = \request()->get('id');
        $phone = Gym::where('id', $id)->where('gym_id', $gymId)->first();
        return $phone['phones'];

    }


    public function uploadImages(Request $request)
    {
        $input_file = 'file';
        $this->uploadFile($request, $input_file);//$this->prepare_inputs($request);
        return Response::json(['target_file' => $this->imageName], 200);
    }


    private function getFileInfo($files)
    {
        $path = asset(GymImage::$uploads_path);
        $video = [];
        foreach ($files as $file) {
            $fileName = basename($file);
//            $headers = get_headers($file, true);
            $video[] = array('name' => $fileName, 'size' => @$headers['Content-Length'], 'path' => $path);
        }
        return $video;
    }
//    public function uploadMainImage(Request $request){
//        $gym_image = $this->prepare_inputs($request);
//        return $gym_image['images'];
//    }

    public function gymsJson()
    {
        $gyms = Gym::select('id', 'gym_brand_id', 'image', 'lat', 'lng')->with(['gym_brand' => function ($q) {
            $q->select('id', 'name_' . $this->lang);
        }])->limit(50)->get()->toJson();
        return $gyms;
    }

    private function prepare_inputs($inputs)
    {
        $input_file = 'file';
        $inputs = $this->uploadFile($inputs, $input_file);
        $input_file = 'cover_image';
        $inputs = $this->uploadFile($inputs, $input_file);
        $input_file = 'image';
        $inputs = $this->uploadFile($inputs, $input_file);

//        $input_file = 'file';
//        $inputs = $this->uploadFile($inputs, $input_file);

        return $inputs;
    }

    private function uploadFile($inputs, $file)
    {

        $input_file = $file;
        $uploaded = '';

        $destinationPath = base_path(GymBrand::$uploads_path);
        $ThumbnailsDestinationPath = base_path(GymBrand::$thumbnails_uploads_path);
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
                $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '-' . rand(0, 20000) . time() . '.' . $file->getClientOriginalExtension();


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
//                    $img->save($destinationPath . $filename);
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
                $inputs[$input_file] = $this->imageName = (string)$uploaded;
            }

        }
        //        !$inputs['deleted_at']?$inputs['deleted_at']=null:'';

        return $inputs;
    }

}

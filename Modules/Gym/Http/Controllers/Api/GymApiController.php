<?php

namespace Modules\Gym\Http\Controllers\Api;

use Modules\Banner\Http\Resources\BannerResource;
use Modules\Banner\Models\Banner;
use Modules\Generic\Classes\Constants;
use Modules\Generic\Http\Controllers\Api\GenericApiController;
use Modules\Generic\Http\Resources\CityDistrictResource;
use Modules\Generic\Models\City;
use Modules\Generic\Repositories\SettingRepository;
use Modules\Gym\Http\Resources\GymCategoryResource;
use Modules\Gym\Http\Resources\GymDetailResource;
use Modules\Gym\Http\Resources\GymMapResource;
use Modules\Gym\Http\Resources\GymResource;
use Modules\Gym\Http\Resources\GymServiceResource;
use Modules\Gym\Models\Category;
use Modules\Gym\Models\Gym;
use Modules\Gym\Models\GymBrand;
use Modules\Gym\Models\GymDiscount;
use Modules\Gym\Models\GymFavorite;
use Modules\Gym\Models\GymImage;
use Modules\Gym\Models\Service;
use Modules\Trainer\Models\Trainer;
use Illuminate\Container\Container as Application;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\Request;

class GymApiController extends GenericApiController
{
    public function gyms(){
        $categoryId = request('category_id');
        $lang = request('lang');
        if(!$this->validateApiRequest())
            return $this->response;

        $gyms = Gym::active()->select('id', 'district_id', 'gym_brand_id');
        $gyms->with(['district.city', 'categories', 'discount']);
        $gyms->with(['gym_brand' => function($q){
            $q->select('id', 'name_en', 'name_ar', 'main_phone', 'logo');
        }]);
        if($categoryId)
            $gyms->whereHas('categories', function ($q) use ($categoryId){
                $q->where('category_id', $categoryId);
            });
        $gyms->orderBy("id", "desc");
        $gyms = $gyms->paginate($this->limit);

        $this->getPaginateAttribute($gyms);
//        $this->returnPaginationData($gyms);
        $this->return['gyms'] = $gyms ? GymResource::collection($gyms) : '';
        $this->return['banner'] = $this->banner($lang, $categoryId);
        return $this->successResponse();
    }
    private function banner($lang, $categoryId){
        $banners = Banner::select('id', 'title', 'image', 'lang', 'category_id', 'gym_id', 'url', 'phone', 'date_from', 'date_to');
        $banners->where('lang', $lang);
        if($categoryId)
            $banners->where('category_id', $categoryId);
        $banners->where('type', Constants::BannerGymType);
        $banners->orWhere('type', null);

        $banner = $banners->orderBy(DB::raw('RAND()'))->first();
        return $banner ? new BannerResource($banner) : '';
    }
    public function gymsOnMap(){
        $city_id = request('city_id');
        $district_id = request('district_id');

        if(!$this->validateApiRequest(['district_id']))
            return $this->response;


        $gyms = Gym::active()->select('id', 'district_id', 'gym_brand_id', 'lat', 'lng');
        $gyms->with(['district.city', 'categories', 'discount']);
        $gyms->where('district_id', $district_id);
        $gyms->where('lat', '!=',null);
        $gyms->when($city_id, function ($query) use ($city_id) {
            $query->whereHas('district', function ($q) use ($city_id){
                $q->where('city_id', $city_id);
            });
        });
        $gyms->with(['gym_brand' => function($q){
            $q->select('id', 'name_en', 'name_ar', 'main_phone', 'logo');
        }]);
        $gyms = $gyms->get();

        $this->return['gyms'] = $gyms ? GymMapResource::collection($gyms) : [];
        return $this->successResponse();
    }
//    public function gymsOnMap(){
//        $lat = request('lat');
//        $lng = request('lng');
//        $distance = 1;
//        if(!$this->validateApiRequest(['lat', 'lng']))
//            return $this->response;
//
//        $gyms = [];
//        $gym_query = DB::select(DB::raw('SELECT id, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $lng . ') ) + sin( radians(' . $lat .') ) * sin( radians(lat) ) ) ) AS distance FROM gyms HAVING distance < ' . $distance . ' ORDER BY distance') );
//        $gym_ids = [];
//
//        foreach($gym_query as $q)
//        {
//            array_push($gym_ids, $q->id);
//        }
//
//        if($gym_ids){
//            $gyms = Gym::select('id', 'district_id', 'gym_brand_id');
//            $gyms->with(['district.city', 'categories', 'discount']);
//
//            $gyms->whereIn('id', $gym_ids);
//
//            $gyms->with(['gym_brand' => function($q){
//                $q->select('id', 'name_en', 'name_ar', 'main_phone', 'logo');
//            }]);
//
//            $gyms->orderBy("updated_at", "desc");
//            $gyms = $gyms->get();
//        }
//
////        $this->returnPaginationData($gyms);
//        $this->return['gyms'] = $gyms ? GymResource::collection($gyms) : [];
//        return $this->successResponse();
//    }

    public function __construct()
    {
        parent::__construct();
    }
    public function gymsWithKeyword(){
        $keyword = request('keyword');
        if(!$this->validateApiRequest())
            return $this->response;

        $gyms = Gym::active()->select('id', 'district_id', 'gym_brand_id');
        $gyms->with(['district.city', 'categories']);
        $gyms->with(['gym_brand' => function($q){
            $q->select('id', 'name_en', 'name_ar', 'main_phone', 'logo');
        }]);
        if($keyword){
            $gyms->whereHas('gym_brand', function ($q) use ($keyword){
                $q->where('name_ar', 'like', '%' .  trim($keyword) . '%');
                $q->orWhere('name_en', 'like', '%' .  trim($keyword) . '%');
            });
            $gyms->whereHas('categories', function ($q) use ($keyword){
                $q->orWhere('name_ar', 'like', '%' .  trim($keyword) . '%');
                $q->orWhere('name_en', 'like', '%' .  trim($keyword) . '%');
            });

            $keywords = explode(' ', $keyword);
            foreach ($keywords as $keyword){
                $gyms->whereHas('gym_brand', function ($q) use ($keyword){
                    $q->orWhere('name_ar', 'like', '%' .  trim($keyword) . '%');
                    $q->orWhere('name_en', 'like', '%' .  trim($keyword) . '%');
                });
                $gyms->whereHas('categories', function ($q) use ($keyword){
                    $q->orWhere('name_ar', 'like', '%' .  trim($keyword) . '%');
                    $q->orWhere('name_en', 'like', '%' .  trim($keyword) . '%');
                });
            }
        }

        $gyms->orderBy("id", "desc");
        $gyms = $gyms->paginate($this->limit);

        $this->getPaginateAttribute($gyms);
        $this->return['gyms'] = $gyms ? GymResource::collection($gyms) : '';
        return $this->successResponse();
    }

    public function gymsWithFilter(){
        $services = $this->mapRecordsToValue(request('services'), 'id');
        $city_id = request('city_id');
        $district_id = request('district_id');
        $discount = request('discount');
        if(!$this->validateApiRequest())
            return $this->response;

        $gyms = Gym::active()->with(['district.city', 'categories', 'services', 'discount']);
        if($services){
            $gyms->whereHas('services', function ($q) use ($services){
                $q->whereIn('service_id', $services);
            });
        }
        if($city_id){
            $gyms->whereHas('district', function ($q) use ($city_id){
                $q->where('city_id', $city_id);
            });
        }
        if($discount){
            $gyms->whereHas('discount', function ($q) use ($discount){
                $q->having('id', '>', 0);
            });
        }
        if($district_id) {
            $gyms->where('district_id', $district_id);
        }
        $gyms->orderBy("id", "desc");
        $gyms = $gyms->paginate($this->limit);

        $this->getPaginateAttribute($gyms);
        $this->return['gyms'] = $gyms ? GymResource::collection($gyms) : '';
        return $this->successResponse();
    }
    public function gym(){

        $id = request('id');
        if(!$this->validateApiRequest(['id']))
            return $this->response;

        $gym = Gym::with(['gym_brand' => function($q){
            $q->select('id', 'name_en', 'name_ar', 'description_en', 'description_ar', 'main_phone', 'logo', 'socials');
        }, 'images', 'categories', 'services', 'district.city', 'discount']);
        $gym->where('id', $id);
        $gym = $gym->orderBy("id", "desc")->first();

        $this->return['gym'] = $gym ? new GymDetailResource($gym) : '';

        if($gym)
            Gym::find($id)->increment('views');


        return $this->successResponse();
    }

    public function myGym(){
        $gym = Gym::with(['gym_brand' => function($q){
            $q->select('id', 'name_en', 'name_ar', 'description_en', 'description_ar', 'main_phone', 'logo', 'socials');
        }, 'images', 'categories', 'services', 'district.city', 'discount']);
        $gym->whereHas('gym_brand', function ($q) {
            $q->where('user_id', $this->api_user->id);
        });

        $gym = $gym->orderBy("id", "desc")->first();

        $this->return['gym_check'] = $gym ? "1" : "0";
        $this->return['gym'] = $gym ? new GymDetailResource($gym) : null;
    }

    public function gymCommon(){
        $this->return['gym_categories'] = GymCategoryResource::collection(Category::orderBy("id", "desc")->get());
        $this->return['gym_services'] = GymServiceResource::collection(Service::orderBy("id", "desc")->get());
        $cities = City::with('district')->get();
        $this->return['cities'] = CityDistrictResource::collection($cities);
        if($this->api_user->id && (request('is_gym_form') == 1)) {
            $this->myGym();
        }
        return $this->successResponse();
    }

    public function categories(){
        if(!$this->validateApiRequest())
            return $this->response;

        $this->return['gym_categories'] = GymCategoryResource::collection(Category::orderBy("id", "desc")->get());
        return $this->successResponse();
    }
    public function favorite(){
        if(!$this->validateApiRequest(['id']))
            return $this->response;
        $id  = request('id');
        $gym = GymFavorite::where(['user_id' => $this->api_user->id, 'gym_id' => $id])->first();
        if(!$gym) {
            GymFavorite::create(['user_id' => $this->api_user->id, 'gym_id' => $id]);

            $this->return['action'] = true;
            $this->return['message'] = trans('global.favorite_add_successfully');
            return $this->successResponse();
        }
        $this->return['action'] = false;
        return $this->falseResponse(trans('global.favorite_not_found'));
    }
    public function deleteFavorite(){
        if(!$this->validateApiRequest(['id']))
            return $this->response;
        $id  = request('id');
        $gym = GymFavorite::where(['user_id' => $this->api_user->id, 'gym_id' => $id])->first();

        if($gym){
            GymFavorite::where(['user_id' => $this->api_user->id, 'gym_id' => $id])->forceDelete();
            $this->return['action'] = true;
            $this->return['message'] = trans('global.favorite_delete_successfully');
            return $this->successResponse();
        }
        $this->return['action'] = false;
        return $this->falseResponse(trans('global.favorite_not_found'));
    }

    public function update(){
        if(!$this->validateApiRequest(['address','city_id','district_id', 'name', 'description', 'categories', 'services', 'main_phone']))
            return $this->response;


        $brand_gym_inputs = [
            'main_phone' => request('main_phone'),
//            'socials' => request('socials'),
        ];
        $brand_gym_inputs['name_en'] = $brand_gym_inputs['name_ar'] = request('name');
        $brand_gym_inputs['description_en'] = $brand_gym_inputs['description_ar'] = request('description');
        $brand_gym_inputs['user_id'] = $this->api_user->id;
        $socials = [];
        $socials['facebook'] = request('facebook') ? request('facebook') : '';
        $socials['website'] = request('website') ? request('website') : '';
        $socials['twitter'] = request('twitter') ? request('twitter') : '';
        $socials['instagram'] = request('instagram') ? request('instagram') : '';
        $socials['linkedin'] = request('linkedin') ? request('linkedin') : '';
        $socials['snapchat'] = request('snapchat') ? request('snapchat') : '';
        $brand_gym_inputs['socials'] = (@array_filter($socials));

        $discount_gym_inputs['discount_description'] = @request('discount_description');
        $discount_gym_inputs['discount_image'] = request('discount_image');

        $gym_images = [];
        $gym_images = request('images');

        $gym_inputs = [
            'address' => request('address'),
            'district_id' => request('district_id'),
            'lat' => request('lat'),
            'lng' => request('lng'),
        ];
        if (\request('phone1') || \request('phone2') ){
            $gym_inputs['phones'] = array_filter([\request('phone1'), \request('phone2')]);
            $gym_inputs['phones'] = array_map(function ($a){
                return str_replace('-', '', $a);
            },$gym_inputs['phones']);
        }

        if((@\request('logo'))){$brand_gym_inputs['logo'] = $this->uploadFile($gym_inputs, 'logo');}
        if((@\request('cover_image'))){$gym_inputs['cover_image'] = $this->uploadFile($gym_inputs, 'cover_image');}
        if((@\request('image'))){$gym_inputs['image'] = $this->uploadFile($gym_inputs, 'image');}


//        $brand_gym_inputs = $this->prepare_inputs($brand_gym_inputs);
//        $discount_gym_inputs = $this->prepare_inputs($discount_gym_inputs);


        $gymBrand = GymBrand::where('user_id', $this->api_user->id)->withTrashed()->first();


//        $images = [];
//        if (request('images')) $images = explode(',', trim(\request('images'), ','));

        $categories = $this->mapRecordsToValue(request('categories'), 'id');
        $services = $this->mapRecordsToValue(request('services'), 'id');

        if ($gymBrand) {
            $gymBrand->update($brand_gym_inputs);
            $gym = Gym::where('gym_brand_id', $gymBrand->id)->first();
            $gym->update($gym_inputs);

            if (isset($categories) && count((array)$categories) > 0) $gym->categories()->sync($categories);
            if (isset($services) && count((array)$services) > 0) $gym->services()->sync($services);

            if(isset($discount_gym_inputs['discount_description']) || isset($discount_gym_inputs['discount_image'])){
                if(isset($discount_gym_inputs['discount_image'])){
                    $discount_gym_inputs['discount_image'] = $this->uploadFile($discount_gym_inputs, 'discount_image');
                    GymDiscount::updateOrCreate(['gym_id' => $gym->id],[ 'gym_id' => $gym->id, 'image' => $discount_gym_inputs['discount_image'], 'description' => $discount_gym_inputs['discount_description']]);
                }else{
                    GymDiscount::updateOrCreate(['gym_id' => $gym->id],[ 'gym_id' => $gym->id, 'description' => $discount_gym_inputs['discount_description']]);
                }
            }

            $this->return['message'] = trans('global.gym_edit_successfully');
        } else {
            $gymBrand = GymBrand::create($brand_gym_inputs);
            $gym_inputs['gym_brand_id'] = $gymBrand->id;
            $gym = Gym::create($gym_inputs);

            if (count((array)$categories) > 0) $gym->categories()->sync($categories);
            if (count((array)$services) > 0) $gym->services()->sync($services);

            if(isset($discount_gym_inputs['discount_description']) || isset($discount_gym_inputs['discount_image'])){
                if(isset($discount_gym_inputs['discount_image'])){
                    $discount_gym_inputs['discount_image'] = $this->uploadFile($discount_gym_inputs, 'discount_image');
                    GymDiscount::updateOrCreate(['gym_id' => $gym->id],[ 'gym_id' => $gym->id, 'image' => $discount_gym_inputs['discount_image'], 'description' => $discount_gym_inputs['discount_description']]);
                }else{
                    GymDiscount::updateOrCreate(['gym_id' => $gym->id],[ 'gym_id' => $gym->id, 'description' => $discount_gym_inputs['discount_description']]);
                }
            }
            $this->return['message'] = trans('global.gym_add_successfully');
        }

        if(isset($gym_images) && (count($gym_images) > 0)){
            foreach($gym_images as $index => $gym_image){
                $gym_image = $this->uploadFile($gym_image, 'images.'.$index);
                GymImage::create(['image' => $gym_image, 'gym_id' => $gym->id]);
            }
        }


        $get_gym = Gym::with(['gym_brand' => function($q){
            $q->select('id', 'name_en', 'name_ar', 'description_en', 'description_ar', 'main_phone', 'logo', 'socials');
        }, 'images', 'categories', 'services', 'district.city', 'discount']);
        $get_gym->where('id', $gym->id);

        $get_gym = $get_gym->orderBy("id", "desc")->first();
        $this->return['gym'] = new GymDetailResource($get_gym);
        return $this->successResponse();
    }

    private function mapRecordsToValue($records, $key = 'id'){
        $values = [];
        if($records){
            $records = json_decode($records);
            if(count($records)){
                foreach ($records as $record){
                    $record = (array)$record;
                    $values[] = $record[$key];
                }
                return $values;
            }
        }
        return null;
    }

    public function deleteImage(Request $request){
        // logo: 1, cover_image: 2, main_image: 3, image: 4
        $gym = Gym::where('id', $request->gym_id)->first();
        $pathinfo = pathinfo($request->image);
        $imageName =  $pathinfo['filename'].'.'.$pathinfo['extension'];
        if(!$this->validateApiRequest(['image','gym_id','type']))
            return $this->response;

        if($request->type == 1){
            GymBrand::where('id' , $gym->gym_brand_id)->update(['logo' => null]);
            @unlink(public_path(GymBrand::$uploads_path.$imageName));
            @unlink(public_path(GymBrand::$thumbnails_uploads_path.$imageName));
        }else if($request->type == 2){
            Gym::where('id' , $gym->id)->update(['cover_image' => null]);
            @unlink(public_path(Gym::$uploads_path.$imageName));
            @unlink(public_path(Gym::$thumbnails_uploads_path.$imageName));
        }else if($request->type == 3){
            Gym::where('id' , $gym->id)->update(['image' => null]);
            @unlink(public_path(Gym::$uploads_path.$imageName));
            @unlink(public_path(Gym::$thumbnails_uploads_path.$imageName));
        }else if($request->type == 5){
            GymDiscount::where('gym_id' , $gym->id)->delete();
            @unlink(public_path(GymDiscount::$uploads_path.$imageName));
            @unlink(public_path(GymDiscount::$thumbnails_uploads_path.$imageName));
        }else {
            GymImage::where(['image' => $imageName, 'gym_id' => $gym->id])->forceDelete();
            @unlink(public_path(GymImage::$uploads_path.$imageName));
            @unlink(public_path(GymImage::$thumbnails_uploads_path.$imageName));
        }

        $this->return['action'] = true;
        return $this->successResponse();
    }

    public function uploadImages(Request $request)
    {
        $input_file = 'file';
        $this->uploadFile($request, $input_file);//$this->prepare_inputs($request);
        return \Illuminate\Support\Facades\Response::json(['target_file' => $this->imageName], 200);
    }


    private function getFileInfo($files)
    {
        $path = asset(GymImage::$uploads_path);
        $video = [];
        foreach ($files as $file) {
            $fileName = basename($file);
            $headers = get_headers($file, true);
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
        }])->get()->toJson();
        return $gyms;
    }

    private function prepare_inputs($inputs, $input_file)
    {
        $inputs = $this->uploadFile($inputs, $input_file);

//        $input_file = 'logo';
//        $inputs = $this->uploadFile($inputs, $input_file);
//        $input_file = 'cover_image';
//        $inputs = $this->uploadFile($inputs, $input_file);
//        $input_file = 'image';
//        $inputs = $this->uploadFile($inputs, $input_file);
//        $input_file = 'discount_image';
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

                $filename = rand(0, 20000) . time() . '.' . $file->getClientOriginalExtension();
//                $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '-' . rand(0, 20000) . time() . '.' . $file->getClientOriginalExtension();


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
                    $img->encode('jpg', 70);
//                    $img->save($destinationPath . $filename);
                    $img->insert($waterMarkUrl, 'bottom-left', 5, 5);
                    $img->resize($new_width, $new_height, function ($constraint) {
                        $constraint->aspectRatio();
                    })->encode('jpg', 70);
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
                    })->encode('jpg', 70)->save($ThumbnailsDestinationPath . '' . $filename);
                } else {
                    //save used image
                    $img->encode('jpg', 70);
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
                    })->encode('jpg', 70)->save($ThumbnailsDestinationPath . '' . $filename);
                }
                return  (string)$uploaded;
            }

        }
        //        !$inputs['deleted_at']?$inputs['deleted_at']=null:'';
        return '';
    }
}

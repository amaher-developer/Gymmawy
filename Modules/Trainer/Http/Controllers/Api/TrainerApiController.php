<?php

namespace Modules\Trainer\Http\Controllers\Api;

use Modules\Banner\Http\Resources\BannerResource;
use Modules\Banner\Models\Banner;
use Modules\Generic\Classes\Constants;
use Modules\Generic\Http\Controllers\Api\GenericApiController;
use Modules\Gym\Models\Category;
use Modules\Trainer\Http\Resources\TrainerCategoryResource;
use Modules\Trainer\Http\Resources\TrainerDetailResource;
use Modules\Trainer\Http\Resources\TrainerResource;
use Modules\Trainer\Models\CategoryTrainer;
use Modules\Trainer\Models\Trainer;
use Modules\Trainer\Models\TrainerFavorite;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class TrainerApiController extends GenericApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function trainers(){
        $categoryId = request('category_id');
        $lang = request('lang');
        if(!$this->validateApiRequest())
            return $this->response;

        $trainers = Trainer::active()->with(['city', 'categories']);
        if($categoryId)
            $trainers->whereHas('categories', function ($q) use ($categoryId){
                $q->where('category_id', $categoryId);
            });
        $trainers->orderBy("updated_at", "desc");
        $trainers = $trainers->paginate($this->limit);
        $this->getPaginateAttribute($trainers);

        $this->return['trainers'] = $trainers ? TrainerResource::collection($trainers) : '';
        $this->return['banner'] = $this->banner($lang, $categoryId);
        return $this->successResponse();
    }
    private function banner($lang, $categoryId){
        $banners = Banner::select('id', 'title', 'image', 'lang', 'category_id', 'gym_id', 'url', 'phone', 'date_from', 'date_to');
        $banners->where('lang', $lang);
        if($categoryId)
            $banners->where('category_id', $categoryId);
        $banners->where('type', Constants::BannerTrainerType);
        $banners->orWhere('type', null);
        $banner = $banners->orderBy(DB::raw('RAND()'))->first();
        return $banner ? new BannerResource($banner) : '';
    }
    public function trainersWithKeyword(){
        $keyword = request('keyword');
        if(!$this->validateApiRequest())
            return $this->response;

        $trainers = Trainer::active()->with(['city', 'categories']);
        if($keyword){
            $trainers->where('name_ar', 'like', '%' .  trim($keyword) . '%');
            $trainers->orWhere('name_en', 'like', '%' .  trim($keyword) . '%');

            $trainers->whereHas('categories', function ($q) use ($keyword){
                $q->orWhere('name_ar', 'like', '%' .  trim($keyword) . '%');
                $q->orWhere('name_en', 'like', '%' .  trim($keyword) . '%');
            });

            $keywords = explode(' ', $keyword);
            foreach ($keywords as $keyword){
                $trainers->orWhere('name_ar', 'like', '%' .  trim($keyword) . '%');
                $trainers->orWhere('name_en', 'like', '%' .  trim($keyword) . '%');

                $trainers->whereHas('categories', function ($q) use ($keyword){
                    $q->orWhere('name_ar', 'like', '%' .  trim($keyword) . '%');
                    $q->orWhere('name_en', 'like', '%' .  trim($keyword) . '%');
                });
            }
        }

        $trainers->orderBy("updated_at", "desc");
        $trainers = $trainers->paginate($this->limit);

        $this->getPaginateAttribute($trainers);

        $this->return['trainers'] = $trainers ? TrainerResource::collection($trainers) : '';
        return $this->successResponse();
    }

    public function trainer(){
        $id = request('id');
        if(!$this->validateApiRequest(['id']))
            return $this->response;

        $trainer = Trainer::with(['categories', 'city'])->where('id', $id)->orderBy("id", "desc")->first();
        $this->return['trainer'] = $trainer ? new TrainerDetailResource($trainer) : $trainer;

        if($trainer)
            Trainer::find($id)->increment('views');

        return $this->successResponse();
    }
    public function myTrainer(){
        if(!$this->validateApiRequest())
            return $this->response;

        $trainer = Trainer::with(['categories', 'city'])->where('user_id', $this->api_user->id)->orderBy("id", "desc")->first();
        $this->return['trainer'] =  $trainer ? new TrainerDetailResource($trainer) : '';

        return $this->successResponse();
    }
    public function categories(){
        if(!$this->validateApiRequest())
            return $this->response;

        $this->return['trainer_categories'] = TrainerCategoryResource::collection(Category::orderBy("id", "desc")->get());

        if($this->api_user->id && (request('is_trainer_form') == 1)){
            $trainer = Trainer::with(['categories', 'city'])->where('user_id', $this->api_user->id)->orderBy("id", "desc")->first();
            $this->return['trainer_check'] =  $trainer ? "1" : "0";
            $this->return['trainer'] =  $trainer ? new TrainerDetailResource($trainer) : null;
        }
        return $this->successResponse();
    }

    public function favorite(){
        if(!$this->validateApiRequest(['id']))
            return $this->response;
        $id  = request('id');
        $trainer = TrainerFavorite::where(['user_id' => $this->api_user->id, 'trainer_id' => $id])->first();

        if(!$trainer){
            TrainerFavorite::create(['user_id' => $this->api_user->id, 'trainer_id' => $id]);
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
        $trainer = TrainerFavorite::where(['user_id' => $this->api_user->id, 'trainer_id' => $id])->first();

        if($trainer){
            TrainerFavorite::where(['user_id' => $this->api_user->id, 'trainer_id' => $id])->forceDelete();
            $this->return['action'] = true;
            $this->return['message'] = trans('global.favorite_delete_successfully');
            return $this->successResponse();
        }
        $this->return['action'] = false;
        return $this->falseResponse(trans('global.favorite_not_found'));
    }
    public function update(){
        if(!$this->validateApiRequest(['name', 'about', 'birthday', 'gender', 'phone', 'categories']))
            return $this->response;

        $birthday = '';
        if(request('birthday')){
//            $birthday = Carbon::createFromFormat('d/m/Y', request('birthday'));
            $birthday = Carbon::parse(request('birthday'))->format('Y-m-d');
        }
        $trainer_inputs = [
//                            'city_id' => request('city_id'),
            'gym_name' => request('gym_name'),
            'birthday' => $birthday,
            'gender' => request('gender'),
            'experience' => request('experience'),
            'phone' => request('phone'),
            'website' => request('website'),
            'facebook' => request('facebook'),
            'twitter' => request('twitter'),
            'instagram' => request('instagram'),
            'linkedin' => request('linkedin'),
            'snapchat' => request('snapchat'),
        ];
        if (\request('phone1') || \request('phone2') ){
            $trainer_inputs['phones'] = array_filter([\request('phone1'), \request('phone2')]);
            $trainer_inputs['phones'] = array_map(function ($a){
                return str_replace('-', '', $a);
            },$trainer_inputs['phones']);
        }
        if(@request('image'))  $trainer_inputs['image'] =  request('image');

        $trainer_inputs = $this->prepare_inputs($trainer_inputs);

        $trainer_inputs['name_en'] = $trainer_inputs['name_ar'] = request('name');
        $trainer_inputs['about_en'] = $trainer_inputs['about_ar'] = request('about');
        $trainer_inputs['user_id'] = $this->api_user->id;
        $trainer = Trainer::where('user_id', $this->api_user->id)->withTrashed()->first();
        $categories = request('categories');
        $categoryIds = [];

        if(isset($categories)){
            $categories = json_decode($categories);
            $categoryIds = collect($categories)->map(function ($category){
                return $category->id;
            });
        }
        if($trainer){
            $trainer->update($trainer_inputs);
            $trainer->categories()->sync($categoryIds);

            $this->return['message'] = trans('global.trainer_edit_successfully');
        }else{
            $trainer = Trainer::create($trainer_inputs);
            $trainer->categories()->attach($categoryIds);

            $this->return['message'] = trans('global.trainer_add_successfully');
        }

        $get_trainer = Trainer::with(['categories', 'city'])->where('id', @$trainer->id)->first();


        $this->return['trainer'] = new TrainerDetailResource($get_trainer) ;
        return $this->successResponse();
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

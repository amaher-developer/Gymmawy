<?php

namespace Modules\Trainer\Models;

use Modules\Access\Models\User;
use Modules\Generic\Models\City;
use Modules\Generic\Models\District;
use Modules\Generic\Models\GenericModel;
use Modules\Gym\Models\Category;
use Modules\Gym\Models\GymFavorite;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Trainer extends GenericModel
{

    protected $dates = ['deleted_at'];

//    protected $table = '';
    protected $guarded = ['id'];
    protected $appends = [ 'name','about', 'image_thumbnail','age', 'birthdate', 'slug', 'gender_name', 'is_favorite'];
    public static $uploads_path='uploads/trainers/';
    public static $thumbnails_uploads_path='uploads/trainers/thumbnails/';
//    public static $uploads_path= 'resources/assets/front/img/demo/trainers/';
//    public static $thumbnails_uploads_path='resources/assets/front/img/demo/trainers/';


    public function scopeActive($query)
    {
        return $query->where('published', 1);
    }

    public function getNameAttribute()
    {
        $lang = 'name_' . ($this->lang ?? session('lang', 'ar'));
        return (string)$this->$lang;
    }


    public function getAboutAttribute()
    {
        $lang = 'about_' . ($this->lang ?? session('lang', 'ar'));
        return (string)$this->$lang;
    }
    public function getGenderNameAttribute()
    {
        $gender = $this->getRawOriginal('gender');
        if($gender == 1)
            return trans('global.male');
        else
            return trans('global.female');
    }

    public function getSlugAttribute()
    {
        $lang = $this->lang ?? session('lang', 'ar');
        return urldecode(str_replace(' ', '-',strtolower($this->getRawOriginal('name_'.$lang))));
    }
    public function getBirthdateAttribute(){
        $date = Carbon::parse($this->birthday);
        return $date->format('Y-m-d');
    }
    public function getAgeAttribute(){
        return Carbon::parse($this->birthday)->diff(Carbon::now())->format('%y '.trans('global.years'));
    }

    public function getIsFavoriteAttribute()
    {
        if(@Auth::guard('api')->user()->id){
            if($this->is_favorite()->where('user_id',  Auth::guard('api')->user()->id)->exists()){
                return true;
            }
        }
        return false;
    }

    public function is_favorite()
    {
        return $this->hasOne(TrainerFavorite::class, 'trainer_id', 'id')
            ->where('user_id' ,  Auth::guard('api')->user()->id);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function districts()
    {
        return $this->belongsToMany(District::class,'district_trainer', 'trainer_id', 'district_id')->withPivot('district_id')->withTimestamps();
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class,'category_trainer', 'trainer_id', 'category_id')->withPivot('category_id')->withTimestamps();
    }
    public function city()
    {
        return $this->belongsTo(City::class,'city_id');
    }

    public function getImageAttribute()
    {
        $image = $this->getRawOriginal('image');
        if($image)
            return asset(self::$uploads_path.$image);
        else
        {
            $lang = $this->lang ?? session('lang', 'ar');
            return asset('resources/assets/front/img/logo/default_'.$lang.'.png');
        }
    }
    
        public function getImageThumbnailAttribute()
    {
        if ($this->image) {
            return str_replace(self::$uploads_path,self::$thumbnails_uploads_path , $this->image);
        }else
        {
            $lang = $this->lang ?? session('lang', 'ar');
            return asset('resources/assets/front/img/logo/default_'.$lang.'.png');
        }
    }


    public function favorites()
    {
        return $this->hasMany(TrainerFavorite::class, 'trainer_id');
    }



    public function toArray()
    {
        return parent::toArray();
        $to_array_attributes = [];
        foreach ($this->relations as $key => $relation) {
            $to_array_attributes[$key] = $relation;
        }
        foreach ($this->appends as $key => $append) {
            $to_array_attributes[$key] = $append;
        }
        return $to_array_attributes;
    }

}

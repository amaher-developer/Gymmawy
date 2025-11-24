<?php

namespace Modules\Gym\Models;

use Modules\Access\Models\User;
use Modules\Generic\Models\District;
use Modules\Generic\Models\GenericModel;
use Modules\Trainer\Models\TrainerFavorite;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Gym extends GenericModel
{

    protected $dates = ['deleted_at'];

//    protected $table = '';
    protected $guarded = ['id'];
    protected $casts = ['phones' => 'object', 'socials' => 'array'];
    public static $uploads_path='uploads/gyms/';
    public static $thumbnails_uploads_path='uploads/gyms/thumbnails/';
    protected $appends = ['slug', 'image_thumbnail', 'is_favorite'];


    public function scopeActive($query)
    {
        return $query->where('published', 1);
    }


    public function getImageAttribute()
    {
        $image = $this->getRawOriginal('image');
        if($image)
        {
            return Asset(self::$uploads_path.$image);
        }
        else
            return asset('resources/assets/front/img/logo/default_'.$this->lang.'.png');
    }

    public function getImageThumbnailAttribute()
    {
        $image = $this->getRawOriginal('image');
        if($image)
        {
            return Asset(self::$thumbnails_uploads_path.$image);
        }
        else
            return asset('resources/assets/front/img/logo/default_'.$this->lang.'.png');
    }


    public function getNameAttribute()
    {
        return strtoupper($this->gym_brand['name_'.$this->lang]);
    }
    public function getDescriptionAttribute()
    {
        return $this->gym_brand['description_'.$this->lang];
    }

    public function getSlugAttribute()
    {
        return urldecode(str_replace(' ', '-',str_replace('&', '', strtolower($this->gym_brand['name_'.$this->lang]))));
    }

    public function getCoverImageAttribute($image)
    {
        if($image)
        {
            return Asset(self::$uploads_path.$image);
        }
        else
            return $image;
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
        return $this->hasOne(GymFavorite::class, 'gym_id', 'id')
            ->where('user_id' ,  Auth::guard('api')->user()->id);
    }
    public function images()
    {
        return $this->hasMany(GymImage::class, 'gym_id');
    }

    public function discounts()
    {
        return $this->hasMany(GymDiscount::class, 'gym_id');
    }
    public function discount()
    {
        return $this->hasOne(GymDiscount::class, 'gym_id')->orderBy('id', 'desc');
    }
    public function call_center_log()
    {
        return $this->hasOne(GymCallCenterLog::class, 'gym_id');
    }

    public function gym_brand()
    {
        return $this->belongsTo(GymBrand::class, 'gym_brand_id');
    }
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
    public function favorites()
    {
        return $this->hasMany(GymFavorite::class, 'gym_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class,'category_gym', 'gym_id', 'category_id')->withPivot('category_id')->withTimestamps();
    }
    public function services()
    {
        return $this->belongsToMany(Service::class,'gym_service', 'gym_id', 'service_id')->withPivot('service_id')->withTimestamps();
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

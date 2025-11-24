<?php

namespace Modules\Banner\Models;

use Modules\Generic\Models\GenericModel;
use Modules\Gym\Models\Category;
use Modules\Gym\Models\Gym;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends GenericModel
{

    protected $dates = ['deleted_at'];

//    protected $table = '';
    protected $guarded = ['id'];
    protected $appends = ['image_thumbnail'];
    public static $uploads_path='uploads/banners/';
    public static $thumbnails_uploads_path='uploads/banners/thumbnails/';


    public function getImageAttribute()
    {
        $image = $this->getRawOriginal('image');
        if($image)
        {
            return Asset(self::$uploads_path.$image);
        }
        else
            return asset('resources/assets/front/img/logo/default.png');
    }

    public function getImageThumbnailAttribute()
    {
        $image = $this->getRawOriginal('image');
        if($image)
        {
            return Asset(self::$thumbnails_uploads_path.$image);
        }
        else
            return asset('resources/assets/front/img/logo/default.png');
    }
    public function getRawOriginalImageAttribute()
    {
        $image = $this->getRawOriginal('image');
        return ($image);
    }
    public function getTypeNameAttribute(){
        $type = $this->getRawOriginal('type');
        if($type == 1)
            return trans('admin.gym');
        elseif($type == 2)
            return trans('admin.trainer');
        elseif($type == 3)
            return trans('admin.home');

        return trans('admin.all');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function gym()
    {
        return $this->belongsTo(Gym::class, 'gym_id');
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

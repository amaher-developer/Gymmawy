<?php

namespace Modules\Gym\Models;

use Modules\Generic\Models\GenericModel;

class GymDiscount extends GenericModel
{

    protected $dates = ['deleted_at'];

    protected $table = 'gym_discounts';
    protected $guarded = ['id'];
    protected $appends = [];
    public static $uploads_path='uploads/gyms/';
    public static $thumbnails_uploads_path='uploads/gyms/thumbnails/';

//    public static $uploads_path= 'resources/assets/front/img/demo/gyms/';
//    public static $thumbnails_uploads_path='resources/assets/front/img/demo/gyms/';

    public function gym()
    {
        return $this->belongsTo(Gym::class, 'gym_id');
    }
    public function getImageAttribute()
    {
        $image = $this->getRawOriginal('image');
        if($image)
            return asset(self::$uploads_path.$image);
        else
            return $image;
    }
    public function getRawOriginalImageAttribute()
    {
        $image = $this->getRawOriginal('image');
        if($image)
            return asset(self::$uploads_path.$image);
        else
            return $image;
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

<?php

namespace Modules\Gym\Models;

use Modules\Generic\Models\GenericModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class GymImage extends GenericModel
{

    protected $dates = ['deleted_at'];

    protected $table = 'gym_images';
    protected $guarded = ['id'];
    protected $appends = [];
    public static $uploads_path='uploads/gyms/';
    public static $thumbnails_uploads_path='uploads/gyms/thumbnails/';

//    public static $uploads_path= 'resources/assets/front/img/demo/gyms/';
//    public static $thumbnails_uploads_path='resources/assets/front/img/demo/gyms/';

    public function gym()
    {
        return $this->belongsTo(GymBrand::class, 'gym_id');
    }
    public function getImageAttribute()
    {
        $image = $this->getRawOriginal('image');
        return asset(self::$uploads_path.$image);
    }
    public function getRawOriginalImageAttribute()
    {
        $image = $this->getRawOriginal('image');
        return ($image);
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

<?php

namespace Modules\Gym\Models;

use Modules\Generic\Models\GenericModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class GymAdvice extends GenericModel
{

    protected $dates = ['deleted_at'];

    protected $table = 'gym_advices';
    protected $guarded = ['id'];
    protected $appends = [];
    public static $uploads_path='uploads/articles/';
    public static $thumbnails_uploads_path='uploads/articles/thumbnails/';


    public function getImageAttribute()
    {
        $image = $this->getRawOriginal('image');
        if($image)
            return asset(self::$thumbnails_uploads_path.$image);
    }

    public function getSlugAttribute()
    {
        return urldecode(str_replace(' ', '-',str_replace('&', '', strtolower($this->getRawOriginal('title')))));
    }
}

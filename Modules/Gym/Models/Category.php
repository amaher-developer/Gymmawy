<?php

namespace Modules\Gym\Models;

use Modules\Generic\Models\GenericModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends GenericModel
{

    protected $dates = ['deleted_at'];

//    protected $table = '';
    protected $guarded = ['id'];
    protected $appends = ['name'];
    public static $uploads_path='uploads/categories/';
    public static $thumbnails_uploads_path='uploads/categories/thumbnails/';

    public function getLogoAttribute($image)
    {
        if($image)
        {
            return asset(self::$uploads_path.$image);
        }
        else
            return $image;
    }
    public function getNameAttribute()
    {
        $lang = 'name_' . $this->lang;
        return (string)$this->$lang;
    }

    public function gyms()
    {
        return $this->belongsToMany(GymBrand::class,'category_gym', 'category_id', 'gym_id')->withPivot('gym_id')->withTimestamps();
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

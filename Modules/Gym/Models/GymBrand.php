<?php

namespace Modules\Gym\Models;

use Modules\Access\Models\User;
use Modules\Generic\Models\District;
use Modules\Generic\Models\GenericModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class GymBrand extends GenericModel
{

    protected $dates = ['deleted_at'];

//    protected $table = '';
    protected $guarded = ['id'];
    protected $appends = ['name', 'description'];
    protected $casts = ['socials' => 'json'];
    public static $uploads_path='uploads/gyms/';
    public static $thumbnails_uploads_path='uploads/gyms/thumbnails/';
//    public static $uploads_path= 'resources/assets/front/img/demo/gyms/';
//    public static $thumbnails_uploads_path='resources/assets/front/img/demo/gyms/';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function getNameAttribute()
    {
        $lang = 'name_' . $this->lang;
        return (string)$this->$lang;
    }


    public function getDescriptionAttribute()
    {
        $lang = 'description_' . $this->lang;
        return (string)$this->$lang;
    }



    public function getLogoAttribute($logo)
    {
        if($logo)
        {
            return Asset(self::$uploads_path.$logo);
        }
        else
            return $logo;
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function gyms()
    {
        return $this->hasMany(Gym::class, 'gym_brand_id');
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

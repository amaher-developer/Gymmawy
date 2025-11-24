<?php

namespace Modules\Generic\Models;

use Modules\Generic\Models\GenericModel;
use Modules\Gym\Models\GymBrand;
use Modules\Gym\Models\Gym;
use Modules\Trainer\Models\Trainer;
use Illuminate\Database\Eloquent\SoftDeletes;
use phpDocumentor\Reflection\Location;

class District extends GenericModel
{

    protected $dates = ['deleted_at'];

//    protected $table = '';
    protected $guarded = ['id'];
    protected $appends = ['name'];
    public static $uploads_path='uploads/districts/';
    public static $thumbnails_uploads_path='uploads/districts/thumbnails/';


    public function getNameAttribute()
    {
        $lang = 'name_' . (session('lang', 'ar'));
        return (string)$this->$lang;
    }

    public function city(){
        return $this->belongsTo(City::class, 'city_id');
    }

    public function trainers()
    {
        return $this->belongsToMany(Trainer::class,'district_trainer', 'district_id', 'trainer_id')->withPivot('trainer_id')->withTimestamps();
    }

    public function branch(){
        return $this->hasOne(Gym::class, 'district_id');
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

<?php

namespace Modules\Store\Models;

use Modules\Generic\Models\GenericModel;
use Modules\Gym\Models\GymBrand;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends GenericModel
{

    protected $dates = ['deleted_at'];

//    protected $table = '';
    protected $guarded = ['id'];
    protected $appends = ['name', 'description'];
    public static $uploads_path='uploads/stores/';
    public static $thumbnails_uploads_path='uploads/stores/thumbnails/';


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

    public function gym(){
        return $this->belongsTo(GymBrand::class, 'gym_id');
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

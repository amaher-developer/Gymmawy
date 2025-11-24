<?php

namespace Modules\Gym\Models;

use Modules\Generic\Models\GenericModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends GenericModel
{

    protected $dates = ['deleted_at'];

//    protected $table = '';
    protected $guarded = ['id'];
    protected $appends = ['name'];
    public static $uploads_path='uploads/services/';
    public static $thumbnails_uploads_path='uploads/services/thumbnails/';

    public function getNameAttribute()
    {
        $lang = 'name_' . $this->lang;
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


    public function gyms()
    {
        return $this->belongsToMany(Service::class,'gym_service', 'service_id', 'gym_id')->withPivot('gym_id')->withTimestamps();
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

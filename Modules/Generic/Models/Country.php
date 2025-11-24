<?php

namespace Modules\Generic\Models;

use Modules\Generic\Models\GenericModel;
use Modules\Trainer\Models\Trainer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends GenericModel
{

    protected $dates = ['deleted_at'];

//    protected $table = '';
    protected $guarded = ['id'];
    protected $appends = ['name', 'flag'];
    public static $uploads_path='uploads/countries/';
    public static $thumbnails_uploads_path='uploads/countries/thumbnails/';


    public function getNameAttribute()
    {
        $lang = 'name_' . (session('lang', 'ar'));
        return (string)$this->$lang;
    }
    public function getFlagAttribute()
    {
        $code = strtolower($this->getRawOriginal('code'));
        return asset(self::$uploads_path.'flags/'.$code.'.png');
    }

    public function city(){
        return $this->hasMany(City::class, 'country_id');
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

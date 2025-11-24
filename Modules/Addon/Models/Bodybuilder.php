<?php

namespace Modules\Addon\Models;

use Modules\Generic\Models\Country;
use Modules\Generic\Models\District;
use Modules\Generic\Models\GenericModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bodybuilder extends GenericModel
{

    protected $dates = ['deleted_at'];

//    protected $table = '';
    protected $guarded = ['id'];
    protected $appends = ['name','description','image_thumbnail', 'slug'];
    public static $uploads_path='uploads/bodybuilders/';
    public static $thumbnails_uploads_path='uploads/bodybuilders/thumbnails/';


    public function getNameAttribute()
    {
        $lang = 'name_'. $this->lang;
        return $this->$lang;
    }
//    public function getBirthdayAttribute($birthday)
//    {
//        return Carbon::parse($birthday)->format('Y');
//    }

    public function getDescriptionAttribute()
    {
        $lang = 'description_'. $this->lang;
        return $this->$lang;
    }
    public function getSlugAttribute()
    {
        $string = str_replace(' ', '-',strtolower(str_replace('&', '', $this->getRawOriginal('name_'.$this->lang))));
        $string = str_replace('/', '', $string);
        return urldecode($string);
    }
    public function getImageAttribute($image)
    {
        if($image)
        {
            return Asset(self::$uploads_path.$image);
        }
        else
            return $image;
    }

    public function getCoverImageAttribute($image)
    {
        if($image)
        {
            return Asset(self::$uploads_path.$image);
        }
        else
            return $image;
    }
    
        public function getImageThumbnailAttribute()
    {
        if ($this->image) {
            return str_replace(self::$uploads_path,self::$thumbnails_uploads_path , $this->image);
        } else
            return $this->image;
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
    public function competitions()
    {
        return $this->hasMany(BodybuilderCompetition::class, 'bodybuilder_id');
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

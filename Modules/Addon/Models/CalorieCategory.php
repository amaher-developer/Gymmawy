<?php

namespace Modules\Addon\Models;

use Modules\Generic\Models\GenericModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class CalorieCategory extends GenericModel
{

    protected $dates = ['deleted_at'];

//    protected $table = '';
    protected $guarded = ['id'];
    protected $appends = ['name','image_thumbnail','slug'];
    public static $uploads_path='uploads/caloriecategories/';
    public static $thumbnails_uploads_path='uploads/caloriecategories/thumbnails/';



    public function getSlugAttribute()
    {
        return urldecode(str_replace(' ', '-',strtolower($this->getRawOriginal('name_'.$this->lang))));
    }


    public function getNameAttribute()
    {
        $lang = 'name_'. $this->lang;
        return $this->$lang;
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
    
        public function getImageThumbnailAttribute()
    {
        if ($this->image) {
            return str_replace(self::$uploads_path,self::$thumbnails_uploads_path , $this->image);
        } else
            return $this->image;
    }


    public function foods(){
        return $this->hasMany(CalorieFood::class, 'category_id');
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

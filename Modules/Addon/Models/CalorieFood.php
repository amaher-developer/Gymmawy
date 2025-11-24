<?php

namespace Modules\Addon\Models;

use Modules\Generic\Models\GenericModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class CalorieFood extends GenericModel
{

    protected $dates = ['deleted_at'];

    protected $table = 'calorie_foods';
    protected $guarded = ['id'];
    protected $appends = ['name'];
    public static $uploads_path='uploads/caloriefoods/';
    public static $thumbnails_uploads_path='uploads/caloriefoods/thumbnails/';


    public function getNameAttribute()
    {
        $lang = 'name_'. $this->lang;
        return $this->$lang;
    }

    public function category(){
        return $this->belongsTo(CalorieCategory::class, 'category_id');
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

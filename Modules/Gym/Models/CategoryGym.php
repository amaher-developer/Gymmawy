<?php

namespace Modules\Gym\Models;

use Modules\Generic\Models\GenericModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryGym extends GenericModel
{

    protected $dates = ['deleted_at'];

    protected $table = 'category_gym';
    protected $guarded = ['id'];
    protected $appends = [];
    public static $uploads_path='uploads/categorygyms/';
    public static $thumbnails_uploads_path='uploads/categorygyms/thumbnails/';





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

<?php

namespace Modules\Gym\Models;

use Modules\Generic\Models\GenericModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class GymCallCenterLog extends GenericModel
{

    protected $dates = ['deleted_at'];

//    protected $table = '';
    protected $guarded = ['id'];
    protected $appends = ['name'];
    public static $uploads_path='uploads/callcenterlogs/';
    public static $thumbnails_uploads_path='uploads/callcenterlogs/thumbnails/';



    public function gym()
    {
        return $this->hasOne(Gym::class,'gym_id');
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

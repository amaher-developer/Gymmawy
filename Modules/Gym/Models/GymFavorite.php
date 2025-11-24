<?php

namespace Modules\Gym\Models;

use Modules\Generic\Models\GenericModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class GymFavorite extends GenericModel
{

    protected $dates = ['deleted_at'];

    protected $table = 'gym_favorites';
    protected $guarded = ['id'];
    protected $appends = [];
    public static $uploads_path='uploads/gymfavorites/';
    public static $thumbnails_uploads_path='uploads/gymfavorites/thumbnails/';


    public function gym(){
        return $this->belongsTo(Gym::class, 'gym_id');
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

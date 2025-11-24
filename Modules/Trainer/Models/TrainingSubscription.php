<?php

namespace Modules\Trainer\Models;

use Modules\Client\Models\Client;
use Modules\Client\Models\TrainingClient;
use Modules\Generic\Models\GenericModel;
use Modules\Generic\Models\Setting;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingSubscription extends GenericModel
{

    protected $dates = ['deleted_at'];

//    protected $table = '';
    protected $guarded = ['id'];
    protected $appends = [];
    public static $uploads_path='uploads/trainers/';
    public static $thumbnails_uploads_path='uploads/trainers/thumbnails/';

    public function getImageAttribute($image)
    {
        if($image)
        {
            return asset(self::$uploads_path.$image);
        }
        else
            return $image;
    }

    public function client()
    {
        return $this->belongsTo(TrainingClient::class,'training_client_id');
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

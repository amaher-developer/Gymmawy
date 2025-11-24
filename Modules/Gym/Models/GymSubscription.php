<?php

namespace Modules\Gym\Models;

use Modules\Client\Models\Client;
use Modules\Generic\Models\GenericModel;
use Modules\Generic\Models\Setting;
use Illuminate\Database\Eloquent\SoftDeletes;

class GymSubscription extends GenericModel
{

    protected $dates = ['deleted_at'];

//    protected $table = '';
    protected $guarded = ['id'];
    protected $appends = [];
    public static $uploads_path='uploads/gyms/';
    public static $thumbnails_uploads_path='uploads/gyms/thumbnails/';



    public function gym()
    {
        return $this->belongsTo(Gym::class,'gym_id');
    }
    public function client()
    {
        return $this->belongsTo(Client::class,'client_id');
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

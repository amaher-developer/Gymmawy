<?php

namespace Modules\Client\Models;

use Modules\Generic\Models\GenericModel;
use Modules\Gym\Models\Gym;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends GenericModel
{

    protected $dates = ['deleted_at'];

    protected $table = 'clients';
    protected $guarded = ['id'];
    protected $appends = [];
    public static $uploads_path='uploads/clients/';
    public static $thumbnails_uploads_path='uploads/clients/thumbnails/';


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

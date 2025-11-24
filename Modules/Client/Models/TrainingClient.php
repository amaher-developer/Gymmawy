<?php

namespace Modules\Client\Models;

use Modules\Access\Models\User;
use Modules\Generic\Models\GenericModel;
use Modules\Trainer\Models\TrainingSubscription;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingClient extends GenericModel
{

    protected $dates = ['deleted_at'];

    protected $table = 'training_clients';
    protected $guarded = ['id'];
    protected $appends = [];
    protected $casts = ['questions' => 'json'];
    public static $uploads_path='uploads/clients/';
    public static $thumbnails_uploads_path='uploads/clients/thumbnails/';


    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function subscriptions(){
        return $this->hasMany(TrainingSubscription::class, 'training_client_id');
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

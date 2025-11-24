<?php

namespace Modules\Ask\Models;

use Modules\Access\Models\User;
use Modules\Generic\Models\GenericModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends GenericModel
{

    protected $dates = ['deleted_at'];

    protected $table = 'ask_answers';
    protected $guarded = ['id'];
    protected $appends = [];
    public static $uploads_path='uploads/asks/';
    public static $thumbnails_uploads_path='uploads/asks/thumbnails/';


    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function child_answers(){
        return $this->hasMany(Answer::class, 'parent_id');
    }
    public function parent_answer(){
        return $this->belongsTo(Answer::class, 'parent_id');
    }
    public function question(){
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function getCreatedAtAttribute($created_at)
    {
        return Carbon::parse($created_at)->toDateString();
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

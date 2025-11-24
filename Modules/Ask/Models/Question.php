<?php

namespace Modules\Ask\Models;

use Modules\Access\Models\User;
use Modules\Article\Models\ArticleCategory;
use Modules\Article\Models\Tag;
use Modules\Generic\Models\GenericModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends GenericModel
{

    protected $dates = ['deleted_at'];

    protected $table = 'ask_questions';
    protected $guarded = ['id'];
    protected $appends = ['slug'];
    public static $uploads_path='uploads/asks/';
    public static $thumbnails_uploads_path='uploads/asks/thumbnails/';

    public function getSlugAttribute()
    {
        $string = str_replace(' ', '-',strtolower(str_replace('&', '', $this->getRawOriginal('question'))));
        $string = str_replace('/', '', $string);
        return urldecode($string);
    }

    public function getCreatedAtAttribute($created_at)
    {
        return Carbon::parse($created_at)->toDateString();
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(){
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }
    public function answers(){
        return $this->hasMany(Answer::class, 'question_id');
    }

    public function tags(){
        return $this->belongsToMany(Tag::class, 'ask_question_tag', 'question_id', 'tag_id');
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

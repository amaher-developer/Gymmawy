<?php

namespace Modules\Article\Models;

use Modules\Access\Models\User;
use Modules\Ask\Models\Question;
use Modules\Generic\Models\GenericModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends GenericModel
{

    protected $dates = ['deleted_at'];

//    protected $table = '';
    protected $guarded = ['id'];
    protected $appends = ['slug'];
    public static $uploads_path='uploads/articles/';
    public static $thumbnails_uploads_path='uploads/articles/thumbnails/';

    public function getSlugAttribute()
    {
        return urldecode(str_replace(' ', '-',strtolower($this->getRawOriginal('name'))));
    }

    public function articles(){
        return $this->belongsToMany(Article::class, 'article_tag', 'tag_id', 'article_id');
    }
    public function questions(){
        return $this->belongsToMany(Question::class, 'ask_question_tag', 'tag_id', 'question_id');
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

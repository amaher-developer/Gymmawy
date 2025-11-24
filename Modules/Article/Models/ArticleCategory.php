<?php

namespace Modules\Article\Models;

use Modules\Generic\Models\GenericModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleCategory extends GenericModel
{

    protected $dates = ['deleted_at'];

//    protected $table = '';
    protected $guarded = ['id'];
    protected $appends = ['slug', 'name'];
    public static $uploads_path='uploads/articlecategories/';
    public static $thumbnails_uploads_path='uploads/articlecategories/thumbnails/';

    public function getNameAttribute()
    {
        $lang = 'name_' . $this->lang;
        return (string)$this->$lang;
    }

    public function getSlugAttribute()
    {
        return urldecode(str_replace(' ', '-',strtolower($this->getRawOriginal('name_'.$this->lang))));
    }

    public function article(){
        return $this->hasMany(Article::class, 'category_id');
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

<?php

namespace Modules\Article\Models;

use Illuminate\Database\Eloquent\Model;


class ArticleImage extends Model
{

    protected $dates = [];

//    protected $table = '';
    protected $guarded = ['id'];
    protected $appends = ['image_with_path'];
    public static $uploads_path='uploads/articles/';
    public static $thumbnails_uploads_path='uploads/articles/thumbnails/';

    public function getImageWithPathAttribute(){
        return asset(self::$uploads_path.$this->getRawOriginal('image'));
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

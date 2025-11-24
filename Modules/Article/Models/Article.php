<?php

namespace Modules\Article\Models;

use Modules\Access\Models\User;
use Modules\Generic\Models\GenericModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends GenericModel
{

    protected $dates = ['deleted_at'];

//    protected $table = '';
    protected $guarded = ['id'];
    protected $appends = [ 'image_thumbnail', 'youtube_link', 'youtube_image', 'arabic_date', 'slug', 'short_description'];
    protected $casts = ['calculates' => 'json'];

    public static $uploads_path='uploads/articles/';
    public static $thumbnails_uploads_path='uploads/articles/thumbnails/';
//    public static $uploads_path= 'resources/assets/front/img/demo/articles/';
//    public static $thumbnails_uploads_path='resources/assets/front/img/demo/articles/';

    public function scopeLanguage($query, $language = null)
    {
        $lang = $language ?? session('lang', 'ar');
        return $query->where('language', $lang);
    }
    public function scopeActive($query)
    {
        return $query->where('published', 1);
    }
//    public function getTitleAttribute()
//    {
//        $lang = 'title_' . $this->lang;
//        return (string)$this->$lang;
//    }
//
//    public function getDescriptionAttribute()
//    {
//        $lang = 'description_' . $this->lang;
//        return (string)$this->$lang;
//    }
//
//    public function getKeywordAttribute()
//    {
//        $lang = 'keyword_' . $this->lang;
//        return (string)$this->$lang;
//    }
    public function getUpdateAtAttribute($updated_at)
    {
        if(!$updated_at)
            return Carbon::now()->format('Y-m-d');
        return Carbon::parse($updated_at)->format('Y-m-d');
    }
    public function getCreatedAtAttribute($created_at)
    {
        if(!$created_at)
            return Carbon::now()->format('Y-m-d');
        return Carbon::parse($created_at)->format('Y-m-d');
    }

    public function getSlugAttribute()
    {
        if($this->getRawOriginal('slug')){
            $string = $this->getRawOriginal('slug');
        }else{
            $string = str_replace(' ', '-',strtolower(str_replace('&', '', $this->getRawOriginal('title'))));
            $string = str_replace('/', '', $string);
        }
        return urldecode($string);
    }
    public function getShortDescriptionAttribute()
    {
        if($this->getRawOriginal('short_description'))
            return $this->getRawOriginal('short_description');

        return \Illuminate\Support\Str::limit(strip_tags($this->getRawOriginal('description')), 100);
    }

    public function getArabicDateAttribute()
    {
        Carbon::setLocale('ar');
        return Carbon::parse($this->getRawOriginal('created_at'))->format('Y-m-d');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function category(){
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }

    public function getImageAttribute()
    {
        $image = $this->getRawOriginal('image');
        if($image)
            return asset(self::$uploads_path.$image);
    }

    public function getYoutubeLinkAttribute()
    {
        return 'https://www.youtube.com/watch?v='.$this->getRawOriginal('youtube');
    }

    public function getYoutubeImageAttribute()
    {
        return 'https://img.youtube.com/vi/'.$this->getRawOriginal('youtube').'/hqdefault.jpg';
    }
    
    public function getImageThumbnailAttribute()
    {
        if ($this->image) {
            return str_replace(self::$uploads_path,self::$thumbnails_uploads_path , $this->image);
        } else
            return $this->image;
    }



    public function tags(){
        return $this->belongsToMany(Tag::class, 'article_tag', 'article_id', 'tag_id');
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

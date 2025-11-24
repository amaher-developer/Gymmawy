<?php

namespace Modules\Generic\Models;


use Modules\Generic\Events\SettingUpdated;
use Illuminate\Support\Facades\Cache;

class Setting extends GenericModel
{
//    protected $table = '';
    protected $guarded = ['id'];
    protected $appends = ['name', 'logo', 'logo_white', 'about', 'terms', 'policy', 'meta_description', 'meta_keywords'];
    public static $uploads_path = 'uploads/settings/';
    public static $uploads_path_wa = 'uploads/settings/wa/';

    protected $dispatchesEvents = ['updated' => SettingUpdated::class];

    public function getNameAttribute()
    {
        $lang = 'name_' . (session('lang', 'ar'));
        return (string)$this->$lang;
    }

    public function getMetaDescriptionAttribute()
    {
        $meta_description = 'meta_description_' . (session('lang', 'ar'));
        return (string)$this->$meta_description;
    }

    public function getMetaKeywordsAttribute()
    {
        $meta_keywords = 'meta_keywords_' . (session('lang', 'ar'));
        $meta_keywords = $this->getRawOriginal($meta_keywords);
//        $meta_keywords = explode('&', $meta_keywords);
        $meta_keywords = str_replace('&', ', ', $meta_keywords);
        return (string)$meta_keywords;
    }

    public function getLogoArAttribute($logo)
    {
        if ($logo) {
            return Asset(self::$uploads_path . $logo);
        } else
            return $logo;
    }

    public function getLogoEnAttribute($logo)
    {

        if ($logo) {
            return Asset(self::$uploads_path . $logo);
        } else
            return $logo;
    }

    public function getLogoWhiteArAttribute($logo)
    {
        if ($logo) {
            return Asset(self::$uploads_path . $logo);
        } else
            return $logo;
    }

    public function getLogoWhiteEnAttribute($logo)
    {

        if ($logo) {
            return Asset(self::$uploads_path . $logo);
        } else
            return $logo;
    }

    public function getLogoAttribute()
    {
        $lang = 'logo_' . (session('lang', 'ar'));
        return $this->$lang;
    }

    public function getLogoWhiteAttribute()
    {
        $lang = 'logo_white_' . (session('lang', 'ar'));
        return $this->$lang;
    }


    public function getAboutAttribute()
    {
        $lang = 'about_' . (session('lang', 'ar'));
        return (string)$this->$lang;
    }

    public function getTermsAttribute()
    {
        $lang = 'terms_' . (session('lang', 'ar'));
        return (string)$this->$lang;
    }

    public function getPolicyAttribute()
    {
        $lang = 'policy_' . (session('lang', 'ar'));
        return (string)$this->$lang;
    }

    public function getMetaKeywordsArAttribute($meta_keywords_ar)
    {

        if ($meta_keywords_ar) {
            return explode('&', $meta_keywords_ar);
        } else
            return $meta_keywords_ar;
    }

    public function getMetaKeywordsEnAttribute($meta_keywords_en)
    {

        if ($meta_keywords_en) {
            return explode('&', $meta_keywords_en);
        } else
            return $meta_keywords_en;
    }

    public function updateSettingWithCache()
    {
        return Cache::put('settings', $this, 60 * 24 * 30);
    }

}

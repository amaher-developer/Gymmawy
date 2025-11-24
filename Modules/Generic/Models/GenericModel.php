<?php

namespace Modules\Generic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GenericModel extends Model
{
    use SoftDeletes;
    public static $table_columns = [];
    public $lang;
    protected $dates = ['deleted_at'];
    protected static $staticLang = null;
    protected static $initializing = false;

    public function __construct(array $attributes = [])
    {
        
        parent::__construct($attributes);
        // Prevent recursion
        if (self::$initializing) {
            parent::__construct($attributes);
            return;
        }
        
        self::$initializing = true;
        
        
        // Initialize static lang once per request
        if (self::$staticLang === null) {
            self::$staticLang = $this->detectLanguage();
        }
        
        // Set instance lang
        $this->lang = self::$staticLang;
        self::$initializing = false;
        
    }
    
    protected function detectLanguage()
    {
        try {
            if (app()->has('request')) {
                $request = app('request');
                
                if ($request->is('api/*')) {
                    $lang = $request->get('lang');
                    return isset($lang) && in_array($lang, explode(',', env('SYSTEM_LANG'))) ? $lang : env('DEFAULT_LANG', 'en');
                } elseif ($request->is('operate/*')) {
                    return app()->getLocale('lang') ? app()->getLocale('lang') : env('DEFAULT_LANG', 'en');
                } else {
                    $lang = session('lang', null);
                    return $lang ?? 'en';
                }
            }
        } catch (\Exception $e) {
            // Silently fail
        }
        
        return env('DEFAULT_LANG', 'en');
    }

//    private function castAllAttributesToString()
//    {
//
//        if (request()->is('api/*')) {
//            if (!key_exists($this->table, self::$table_columns)) {
//                $columns = DB::getSchemaBuilder()->getColumnListing($this->table);
//                self::$table_columns[$this->table] = $columns;
//            } else {
//                $columns = self::$table_columns[$this->table];
//            }
//
//
//            foreach ($columns as $column) {
//
//                $this->casts[$column] = 'string';
//            }
//        }
//    }

}

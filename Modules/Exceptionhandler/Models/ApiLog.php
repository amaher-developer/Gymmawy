<?php

namespace Modules\Exceptionhandler\Models;


use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
//    protected $table = '';
    protected $guarded = ['id'];
    protected $appends = [];
    public static $uploads_path='uploads/apilogs/';




}

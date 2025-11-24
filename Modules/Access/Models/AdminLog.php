<?php

namespace Modules\Access\Models;


use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
//    protected $table = '';
    protected $guarded = ['id'];
    protected $appends = [];



    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}

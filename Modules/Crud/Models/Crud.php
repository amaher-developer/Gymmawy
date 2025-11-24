<?php

namespace Modules\Crud\Models;

use Illuminate\Database\Eloquent\Model;

class Crud extends Model
{
    protected $table = 'modules';
    protected $guarded = [];
    public $timestamps = true;
}

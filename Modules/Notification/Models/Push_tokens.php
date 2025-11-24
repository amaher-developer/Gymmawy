<?php

namespace Modules\Notification\Models;

use Modules\Generic\Models\GenericModel;

class Push_tokens extends GenericModel
{
    protected $table = 'push_tokens';
    protected $guarded = [];
}

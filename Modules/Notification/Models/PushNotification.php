<?php

namespace Modules\Notification\Models;

use Modules\Generic\Models\GenericModel;

class PushNotification extends GenericModel
{
    protected $table = 'push_notifications';
    protected $guarded = ['id'];
    protected $appends = [];
    public static $uploads_path = 'uploads/notifications/';
    public $casts = ['body' => 'json'];
}

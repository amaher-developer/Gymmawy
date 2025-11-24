<?php

namespace Modules\Notification\Http\enums;

abstract class NotificationType
{
    const

        external_url = 1,
        general_message = 2,

        approve_item = 3,
        reject_item = 4,
        deactivate_item = 5,

        add_offer = 6,
        reject_offer = 7;
}
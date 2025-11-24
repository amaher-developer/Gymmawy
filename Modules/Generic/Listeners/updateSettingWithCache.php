<?php

namespace Modules\Generic\Listeners;

use Modules\Generic\Events\SettingUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class updateSettingWithCache
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SettingUpdated  $event
     * @return void
     */
    public function handle(SettingUpdated $event)
    {
        $event->settings->updateSettingWithCache();
    }
}

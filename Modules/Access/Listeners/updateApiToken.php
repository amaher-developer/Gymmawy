<?php

namespace Modules\Access\Listeners;

use Modules\Access\Events\UserCreating;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class updateApiToken
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
     * @param  UserCreating  $event
     * @return void
     */
    public function handle(UserCreating $event)
    {
        $event->user->updateApiToken();
    }
}

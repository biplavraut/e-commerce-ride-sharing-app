<?php

namespace App\Listeners;

use App\Events\YoEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class YoEventListener
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
     * @param  YoEvent  $event
     * @return void
     */
    public function handle(YoEvent $event)
    {

    }
}

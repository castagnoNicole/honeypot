<?php

namespace App\Listeners;

use App\Events\PictureUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogPictureUpdated
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PictureUpdated $event): void
    {
        $name = $event->user->name;
        Log::info("[Picture Update] user: $name");
    }
}

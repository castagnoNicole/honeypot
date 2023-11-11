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
        $ip_address = Request()->getClientIp();
        $url = Request()->path();
        $method = Request()->method();
        Log::info("[Picture Update] user: $name, url: $url , method: $method, ip: $ip_address");
    }
}

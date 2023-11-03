<?php

namespace App\Listeners;

use App\Events\HoneypotUserRetrieved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogHoneypotUserRetrieved
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
    public function handle(HoneypotUserRetrieved $event): void
    {
        $ip_address = Request()->getClientIp();
        $url = Request()->path();
        $method = Request()->method();
        Log::info("[Fake Admin Logged in] url: $url , method: $method, ip: $ip_address");
    }
}

<?php

namespace App\Listeners;

use App\Events\XSSAttempt;
use App\Events\XSSDetected;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogXSSAttempt
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
    public function handle(XSSAttempt $event): void
    {
        $name = $event->username;
        $ip_address = Request()->getClientIp();
        $url = Request()->path();
        $method = Request()->method();
        Log::info("[Attempted XSS] user: $name, payload: , url: $url , method: $method, ip: $ip_address");
    }
}

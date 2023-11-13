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
        $name = $event->user->name;
        $ip_address = Request()->getClientIp();
        $url = Request()->path();
        $method = Request()->method();
        $time = date_timestamp_get(date_create());
        //Log::info("[Fake Admin Logged in] user: $name, url: $url , method: $method, ip: $ip_address");
        Log::info("{\"event\": \"fake-admin-logged-in\", \"time\": $time, \"host\": \"group15web\", \"client\": \"$ip_address\", \"user\": \"$name\", \"payload\": \"\", \"method\": \"$method\", \"url\": \"$url\"}");
    }
}

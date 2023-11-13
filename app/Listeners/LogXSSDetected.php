<?php

namespace App\Listeners;

use App\Events\XSSDetected;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogXSSDetected
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
    public function handle(XSSDetected $event): void
    {
        $name = $event->user ? $event->user->getOriginal('name') :'guest';
        $ip_address = Request()->getClientIp();
        $url = Request()->path();
        $method = Request()->method();
        $time = date_timestamp_get(date_create());
        //Log::info("[Detected XSS] user: $name, payload: $event->payload, url: $url , method: $method, ip: $ip_address");
        //{ "event": "ssh-honeypot-auth", "time": 1699888053, "host": "group15web", "client": "172.20.1.140", "user": "username", "pass": "administrator" }
        Log::info("{\"event\": \"xss-detected\", \"time\": $time, \"host\": \"group15web\", \"client\": \"$ip_address\", \"user\": \"$name\", \"payload\": \"$event->payload\", \"method\": \"$method\", \"url\": \"$url\"}");
    }
}

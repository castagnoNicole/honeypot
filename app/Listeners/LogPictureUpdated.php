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
        $time = date_timestamp_get(date_create());
        //{ "event": "ssh-honeypot-auth", "time": 1699888053, "host": "group15web", "client": "172.20.1.140", "user": "username", "pass": "administrator" }
        //Log::info("[Picture Update] user: $name, url: $url , method: $method, ip: $ip_address");
        Log::info("{\"event\": \"picture-update\", \"time\": $time, \"host\": \"group15web\", \"ip\":  \"$ip_address\", \"user\": \"$name\", \"method\": \"$method\", \"url\": \"$url\"}");
    }
}

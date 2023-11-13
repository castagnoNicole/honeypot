<?php

namespace App\Listeners;

use App\Events\SQLinjectionAttempted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogSQLinjectionAttempted
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
    public function handle(SQLinjectionAttempted $event): void
    {
        $name = json_encode($event->user ? $event->user->getOriginal('name') :'guest');
        $ip_address = Request()->getClientIp();
        $url = Request()->path();
        $method = Request()->method();
        $time = date_timestamp_get(date_create());
        $payload =  json_encode($event->payload);
        //Log::info("[SQL injection Attempt] user: $name, payload: $event->payload, url: $url , method: $method, ip: $ip_address");
        Log::info("{\"event\": \"sql-injection-detected\", \"time\": $time, \"host\": \"group15web\", \"client\": \"$ip_address\", \"user\": $name, \"payload\": $payload, \"method\": \"$method\", \"url\": \"$url\"}");
    }
}

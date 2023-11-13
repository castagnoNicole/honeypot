<?php

namespace App\Listeners;

use App\Events\AttemptedBrokenAccessControl;
use http\Env\Request;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogAttemptedBrokenAccessControl
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
    public function handle(AttemptedBrokenAccessControl $event): void
    {
        $name = Request()->name;
        $password = Request()->password;
        $ip_address = Request()->getClientIp();
        $url = Request()->path();
        $method = Request()->method();
        $time = date_timestamp_get(date_create());
        //Log::info("[Broken Access Control Attempt] user: $name, password: $password url: $url , method: $method, ip: $ip_address");
        Log::info("{\"event\": \"broken-access-control-attempted\", \"time\": $time, \"host\": \"group15web\", \"client\": \"$ip_address\", \"user\": \"$name\", \"payload\": \"$password\", \"method\": \"$method\", \"url\": \"$url\"}");
    }
}

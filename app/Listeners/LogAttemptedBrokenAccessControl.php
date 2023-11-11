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
        Log::info("[Attempt Broken Access Control] user: $name, password: $password url: $url , method: $method, ip: $ip_address");
    }
}

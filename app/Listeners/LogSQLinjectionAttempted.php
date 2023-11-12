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
        $name = $event->user ? $event->user->getOriginal('name') :'guest';
        $ip_address = Request()->getClientIp();
        $url = Request()->path();
        $method = Request()->method();
        Log::info("[SQL injection Attempt] user: $name, payload: $event->payload, url: $url , method: $method, ip: $ip_address");
    }
}

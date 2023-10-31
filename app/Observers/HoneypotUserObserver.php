<?php

namespace App\Observers;

use App\Events\HoneypotUserRetrieved;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

class HoneypotUserObserver
{
     public function retrieved(User $user): void
      {
        if ($user->id == 1) {
          event(new HoneypotUserRetrieved($user));
        }
      }
}

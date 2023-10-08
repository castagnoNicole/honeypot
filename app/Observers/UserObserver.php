<?php

namespace App\Observers;
use App\Events\XSSDetected;
use App\Models\User;

class UserObserver
{
    public function retrieved(User $user): void{
        if (is_challenge_xss($user->name)){
            event(new XSSDetected($user, $user->name));
        }
    }
}

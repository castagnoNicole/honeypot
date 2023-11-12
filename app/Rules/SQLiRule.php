<?php

namespace App\Rules;

use App\Events\SQLinjectionAttempted;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class SQLiRule implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $sql_injection_payloads = array(
            "OR 1=1",
            "OR 1=0",
            "OR x=x",
            "OR x=y",
            "OR 1=1#",
            "OR 1=0#",
            "OR x=x#",
            "OR x=y#",
            "OR 1=1-- ",
            "OR 1=0-- ",
            "OR x=x-- ",
            "OR x=y--",
        );

        if (in_array($value, $sql_injection_payloads)) {
            $fail('The :attribute SQL attack has been detected.');
            event(new SQLinjectionAttempted(auth()->user(), $value));
        }
    }


}

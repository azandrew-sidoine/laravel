<?php

namespace App\Support\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Prohibited implements ValidationRule
{
    /**
     * Error message
     * 
     * @var string
     */
    private $message = ':attribute should not be provided';

    public function validate(string $attribute, $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }

        $fail($this->message);
    }
}

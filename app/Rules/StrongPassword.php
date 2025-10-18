<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPassword implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if password has at least 8 characters
        if (strlen($value) < 8) {
            $fail('The :attribute must be at least 8 characters long.');
        }

        // Check if password contains at least one uppercase letter
        if (!preg_match('/[A-Z]/', $value)) {
            $fail('The :attribute must contain at least one uppercase letter.');
        }

        // Check if password contains at least one lowercase letter
        if (!preg_match('/[a-z]/', $value)) {
            $fail('The :attribute must contain at least one lowercase letter.');
        }

        // Check if password contains at least one digit
        if (!preg_match('/[0-9]/', $value)) {
            $fail('The :attribute must contain at least one digit.');
        }

        // Check if password contains at least one special character
        if (!preg_match('/[\W_]/', $value)) {
            $fail('The :attribute must contain at least one special character.');
        }
    }
}
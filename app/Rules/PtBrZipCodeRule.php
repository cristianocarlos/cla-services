<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PtBrZipCodeRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void {
        if (!preg_match('/^[0-9]{5}-[0-9]{3}$/', $value)) {
            $fail('The :attribute must be in the format XXXXX-XXX.');
        }
    }
}

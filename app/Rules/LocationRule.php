<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class LocationRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $pattern = '/^(-?\d+(\.\d+)?),(-?\d+(\.\d+)?)$/';
        if (!preg_match($pattern, $value))
            $fail('The :attribute must be a location. format: latitude,longitude');
    }
}

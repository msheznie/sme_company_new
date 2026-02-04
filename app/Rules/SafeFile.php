<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SafeFile implements Rule
{
    protected $message = '';

    public function passes($attribute, $value)
    {
        if (!$value) {
            $this->message = 'Invalid file uploaded.';
            return false;
        }

        if (is_string($value)) {
            $content = substr($value, 0, 200000);
        }
        else {
            if (!$value->isValid()) {
                $this->message = 'Invalid file uploaded.';
                return false;
            }

            $content = file_get_contents(
                $value->getRealPath(),
                false,
                null,
                0,
                200000
            );
        }

        $maliciousPatterns = [
            '/\/JavaScript\b/i',
            '/\/OpenAction\b/i',
            '/\/Launch\b/i',
            '/\/EmbeddedFile\b/i',
        ];

        foreach ($maliciousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                $this->message = trans('common.the_file_contains_malicious_or_unsafe_content');
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return $this->message ?: trans('common.the_file_contains_malicious_or_unsafe_content');
    }
}

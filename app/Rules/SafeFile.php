<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SafeFile implements Rule
{
    protected $message = '';

    public function passes($attribute, $value)
    {
        if (!$value || !$value->isValid()) {
            $this->message = 'Invalid file uploaded.';
            return false;
        }

        $content = file_get_contents(
            $value->getRealPath(),
            false,
            null,
            0,
            200000 // scan first 200KB only
        );

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

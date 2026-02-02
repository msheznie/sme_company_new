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

        // Read the raw content
        $content = is_string($value) ? $value : $value->get();

        // Dangerous PDF patterns
        $maliciousPatterns = [
            '/\/JavaScript/i',
            '/\/OpenAction/i',
            '/\/Launch/i',
            '/\/EmbeddedFile/i',
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

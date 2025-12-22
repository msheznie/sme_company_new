<?php

return [
    /*
    |--------------------------------------------------------------------------
    | CSRF Protection Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for CSRF token protection
    | in API routes. You can enable/disable CSRF protection and configure
    | token expiry times for different types of requests.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | CSRF Protection Enabled
    |--------------------------------------------------------------------------
    |
    | Set to true to enable CSRF protection for API routes.
    | Set to false to disable CSRF protection completely.
    |
    */
    'enabled' => env('CSRF_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | CSRF Secret Key
    |--------------------------------------------------------------------------
    |
    | This key is used to generate and verify CSRF tokens.
    | Make sure this matches the secret key used in your frontend application.
    | Generate a strong, random key for production use.
    |
    */
    'secret_key' => env('CSRF_SECRET_KEY', 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855'),

    /*
    |--------------------------------------------------------------------------
    | Token Expiry Times
    |--------------------------------------------------------------------------
    |
    | Configure how long CSRF tokens remain valid for different types of requests.
    | Times are specified in seconds.
    |
    */
    'token_expiry_time' => env('CSRF_TOKEN_EXPIRY_TIME', 5),
    'image_token_expiry_time' => env('CSRF_IMAGE_TOKEN_EXPIRY_TIME', 10),

    /*
    |--------------------------------------------------------------------------
    | Protected HTTP Methods
    |--------------------------------------------------------------------------
    |
    | Specify which HTTP methods should be protected by CSRF tokens.
    | Only these methods will require valid CSRF tokens.
    |
    */
    'protected_methods' => ['GET', 'POST', 'PUT', 'DELETE'],

    /*
    |--------------------------------------------------------------------------
    | Excluded Routes
    |--------------------------------------------------------------------------
    |
    | Specify routes that should be excluded from CSRF protection.
    | Use patterns to match multiple routes.
    |
    */
    'excluded_routes' => [
        // Add route patterns here that should be excluded from CSRF protection
        // Example: 'api/public/*'
    ],

    /*
    |--------------------------------------------------------------------------
    | Upload Detection Patterns
    |--------------------------------------------------------------------------
    |
    | Specify URL patterns that should be treated as upload requests
    | and use the extended token expiry time.
    |
    */
    'upload_patterns' => [
        'upload',
        'file-upload',
        'image-upload',
        'document-upload'
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Configure logging for CSRF token validation.
    | Set to true to enable detailed logging of CSRF validation attempts.
    |
    */
    'enable_logging' => env('CSRF_ENABLE_LOGGING', true),
];

<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class VerifyCsrfTokenForApi
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!config('csrf.enabled', true)) {
            return $next($request);
        }

        if (!in_array($request->method(), config('csrf.protected_methods', ['GET', 'POST', 'PUT', 'DELETE']))) {
            return $next($request);
        }

        // Get CSRF token from the request headers
        $signature = $request->header('X-CSRF-TOKEN');
        if (!$signature) {
            Log::warning('CSRF token missing from request headers');
            return $this->sendError(1, 'CSRF token missing');
        }

        $parts = explode('|', $signature);

        // Validate the token structure
        if (count($parts) !== 2) {
            Log::warning('CSRF token structure invalid', [
                'token_parts' => count($parts),
                'signature' => $signature
            ]);
            return $this->sendError(2, 'Invalid CSRF token structure');
        }

        [$csrfToken, $timestamp] = $parts;

        $fullUrl = $this->replaceUrl(urldecode($request->fullUrl()));
        $timeExpiry = $this->getTokenExpiryTime($request);

        Log::info('CSRF validation details', [
            'full_url' => $fullUrl,
            'time_expiry' => $timeExpiry,
            'frontend_timestamp' => $timestamp,
            'current_timestamp' => time(),
            'time_difference' => abs(time() - (int)($timestamp))
        ]);

        // Check token expiry
        if (!$timestamp || abs(time() - (int)($timestamp)) > $timeExpiry) {
            Log::warning('CSRF token expired', [
                'frontend_timestamp' => $timestamp,
                'current_timestamp' => time(),
                'time_difference' => abs(time() - (int)($timestamp)),
                'max_allowed_difference' => $timeExpiry
            ]);
            return $this->sendError(3, 'CSRF token expired');
        }

        $requestBody = $request->getContent() ?: '{}';
        $operation = strtoupper($request->method());
        $operationString = strtolower($operation);

        $combinedData = "{$requestBody}|{$fullUrl}|{$operationString}";
        $encodedRequest = base64_encode($combinedData);

        $dataWithTimestamp = "{$encodedRequest}|{$timestamp}";
        $expectedToken = hash_hmac('sha256', $dataWithTimestamp, config('csrf.secret_key'));

        Log::info('CSRF Backend Debug:', [
            'full_url' => $fullUrl,
            'request_body' => $requestBody,
            'operation' => $operation,
            'operation_string' => $operationString,
            'combined_data' => $combinedData,
            'encoded_request' => $encodedRequest,
            'timestamp' => $timestamp,
            'data_with_timestamp' => $dataWithTimestamp,
            'expected_token' => $expectedToken,
            'provided_token' => $csrfToken,
            'secret_key' => config('csrf.secret_key') ? 'Set' : 'Not Set',
            'secret_key_value' => config('csrf.secret_key')
        ]);

        if (!hash_equals($expectedToken, $csrfToken)) {
            Log::warning('CSRF token validation failed', [
                'expected' => $expectedToken,
                'provided' => $csrfToken,
                'timeExpiry' => $timeExpiry,
                'frontend_timestamp' => $timestamp,
                'current_timestamp' => time(),
                'full_url' => $fullUrl,
                'request_body' => $requestBody,
                'operation' => $operation,
                'operation_string' => $operationString,
                'encoded_request' => $encodedRequest,
                'data_with_timestamp' => $dataWithTimestamp
            ]);

            return $this->sendError(4, 'Invalid CSRF token');
        }

        Log::info('CSRF token validation successful');
        return $next($request);
    }

    /**
     * Send error response
     *
     * @param int $type
     * @param string $message
     * @return Response
     */
    private function sendError($type, $message = 'Invalid CSRF token')
    {
        Log::error('CSRF validation error', [
            'error_type' => $type,
            'message' => $message
        ]);

        return response()->json([
            'error' => $message,
            'error_type' => $type,
            'success' => false
        ], Response::HTTP_FORBIDDEN);
    }

    /**
     * Replace URL to match frontend processing
     *
     * @param string $urlEncode
     * @return string
     */
    public function replaceUrl($urlEncode)
    {
        return preg_replace(
            '/(\?[^?&=]+=[^?&=]+)\?([^?&=]+=[^?&=]+)/',
            '$1&$2',
            preg_replace('/^https?:\/\//', '', $urlEncode)
        );
    }

    /**
     * Get token expiry time based on request type
     *
     * @param Request $request
     * @return int
     */
    private function getTokenExpiryTime($request)
    {
        $fullUrl = $this->replaceUrl(urldecode($request->fullUrl()));
        
        // Check if this is an upload request based on URL patterns
        $isUploadRequest = false;
        foreach (config('csrf.upload_patterns', ['upload']) as $pattern) {
            if (Str::contains($fullUrl, $pattern)) {
                $isUploadRequest = true;
                break;
            }
        }
        
        if ($isUploadRequest) {
            $timeExpiry = config('csrf.image_token_expiry_time', 10);
        } elseif (
            isset($request['extra']) &&
            is_array($request['extra']) &&
            isset($request['extra']['attachment']) &&
            is_array($request['extra']['attachment'])
        ) {
            $timeExpiry = config('csrf.image_token_expiry_time', 10);
        } else {
            $timeExpiry = config('csrf.token_expiry_time', 5);
        }
        
        return $timeExpiry;
    }
}

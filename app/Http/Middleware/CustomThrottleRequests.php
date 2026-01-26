<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Routing\Middleware\ThrottleRequests;

class CustomThrottleRequests extends ThrottleRequests
{
    /**
     * Override to always return JSON instead of HTML.
     */
    protected function buildResponse($key, $maxAttempts)
    {
        $retryAfter = $this->limiter->availableIn($key);

        return response()->json([
            'response_code' => 429,
            'status' => 'error',
            'message' => 'Terlalu banyak percobaan login. Coba lagi dalam ' . $retryAfter . ' detik.',
        ], 429);
    }
}

<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $e)
    {
        if ($e instanceof ThrottleRequestsException) {  
            return response()->json([
                'response_code' => 429,
                'status' => 'error',
                'message' => 'Terlalu banyak percobaan login. Coba lagi dalam 5 menit.',
            ], 429);
        }

        return parent::render($request, $e);
    }
}

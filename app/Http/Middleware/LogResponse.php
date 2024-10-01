<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogResponse
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Log the response
        Log::channel('response')->info('Response Log', [
            'status' => $response->status(),
            'headers' => $response->headers->all(),
            'content' => $response->getContent(),
        ]);

        return $response;
    }
}

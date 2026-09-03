<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogRequestResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Skip log viewer routes to avoid infinite loop
        if (str_starts_with($request->path(), 'log-viewer') || str_starts_with($request->path(), 'logs/')) {
            return $response;
        }

        Log::channel('custom')->info('HTTP Request/Response', [
            'method'      => $request->method(),
            'url'         => $request->fullUrl(),
            'ip'          => $request->ip(),
            'user_id'     => $request->user()?->id,
            'status_code' => $response->getStatusCode(),
            'user_agent'  => $request->userAgent(),
        ]);

        return $response;
    }
}

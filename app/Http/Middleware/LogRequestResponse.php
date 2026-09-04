<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogRequestResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Avoid logging the logging system itself.
        if (
            str_starts_with($request->path(), 'log-viewer') ||
            str_starts_with($request->path(), 'logs/')
        ) {
            return $response;
        }

        $context = [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_id' => $request->user()?->id,
            'status_code' => $response->getStatusCode(),
            'user_agent' => $request->userAgent(),
        ];

        /*
        |--------------------------------------------------------------------------
        | File Log
        |--------------------------------------------------------------------------
        */

        Log::channel('custom')->info(
            'HTTP Request/Response',
            $context
        );

        /*
        |--------------------------------------------------------------------------
        | Database Log
        |--------------------------------------------------------------------------
        */

        try {
            DB::table('logs')->insert([
                'level' => 'info',
                'message' => 'HTTP Request/Response',
                'channel' => 'custom',
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_id' => $request->user()?->id,
                'context' => json_encode([
                    'status_code' => $response->getStatusCode(),
                    'user_agent' => $request->userAgent(),
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $exception) {

            // Do not break the actual request if DB logging fails.
            Log::channel('custom')->error(
                'Database log insertion failed',
                [
                    'error' => $exception->getMessage(),
                ]
            );
        }

        return $response;
    }
}
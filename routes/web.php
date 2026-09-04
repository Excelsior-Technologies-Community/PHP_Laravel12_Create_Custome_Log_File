<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogViewerController;

/*
|--------------------------------------------------------------------------
| Original Test Routes
|--------------------------------------------------------------------------
*/

Route::get('/test-log', function () {

    Log::channel('custom')->info(
        'Custom Log: Info message'
    );

    Log::channel('custom')->warning(
        'Custom Log: Warning message'
    );

    Log::channel('custom')->error(
        'Custom Log: Error message'
    );

    return 'Custom logs written successfully!';
});


Route::get('/file-test', function () {

    file_put_contents(
        storage_path('logs/custom.log'),
        "This is a manual test log.\n",
        FILE_APPEND
    );

    return 'Manual LOG WRITTEN — check storage/logs/custom.log';
});


/*
|--------------------------------------------------------------------------
| Context Logging
|--------------------------------------------------------------------------
*/

Route::get('/test-context-log', function () {

    Log::channel('custom')->info(
        'User Login Event',
        [
            'user_id' => 1,
            'ip' => request()->ip(),
            'url' => request()->fullUrl(),
            'agent' => request()->userAgent(),
        ]
    );

    Log::channel('custom')->warning(
        'Low Stock Warning',
        [
            'product_id' => 42,
            'stock' => 3,
            'ip' => request()->ip(),
        ]
    );

    Log::channel('custom')->error(
        'Payment Failed',
        [
            'user_id' => 1,
            'amount' => 999,
            'ip' => request()->ip(),
            'error_code' => 'CARD_DECLINED',
        ]
    );

DB::table('logs')->insert([
    [
        'level' => 'info',
        'message' => 'User Login Event',
        'channel' => 'custom',
        'ip' => request()->ip(),
        'user_id' => 1,
        'url' => request()->fullUrl(),
        'method' => request()->method(),
        'context' => json_encode([
            'user_id' => 1,
        ]),
        'created_at' => now(),
        'updated_at' => now(),
    ],

    [
        'level' => 'warning',
        'message' => 'Low Stock Warning',
        'channel' => 'custom',
        'ip' => request()->ip(),
        'user_id' => null, // IMPORTANT
        'url' => request()->fullUrl(),
        'method' => request()->method(),
        'context' => json_encode([
            'product_id' => 42,
            'stock' => 3,
        ]),
        'created_at' => now(),
        'updated_at' => now(),
    ],

    [
        'level' => 'error',
        'message' => 'Payment Failed',
        'channel' => 'custom',
        'ip' => request()->ip(),
        'user_id' => 1,
        'url' => request()->fullUrl(),
        'method' => request()->method(),
        'context' => json_encode([
            'amount' => 999,
            'error_code' => 'CARD_DECLINED',
        ]),
        'created_at' => now(),
        'updated_at' => now(),
    ],
]);

    return 'Context logs written to file + database!';
});


/*
|--------------------------------------------------------------------------
| Daily Rotating Log
|--------------------------------------------------------------------------
*/

Route::get('/test-daily-log', function () {

    Log::channel('custom_daily')->info(
        'Daily Log: Info message'
    );

    Log::channel('custom_daily')->warning(
        'Daily Log: Warning message'
    );

    Log::channel('custom_daily')->error(
        'Daily Log: Error message'
    );

    return 'Daily rotating logs written to storage/logs/custom/';
});


/*
|--------------------------------------------------------------------------
| Existing File Log Viewer
|--------------------------------------------------------------------------
*/

Route::get(
    '/log-viewer',
    [LogViewerController::class, 'index']
)->name('log.viewer');


Route::get(
    '/logs/{level}',
    [LogViewerController::class, 'filterByLevel']
)
    ->name('logs.level')
    ->where(
        'level',
        'info|warning|error|debug|critical'
    );


/*
|--------------------------------------------------------------------------
| Download
|--------------------------------------------------------------------------
*/

Route::get(
    '/logs/download',
    [LogViewerController::class, 'download']
)->name('logs.download');


/*
|--------------------------------------------------------------------------
| Database Logs
|--------------------------------------------------------------------------
*/

Route::get(
    '/logs/db',
    [LogViewerController::class, 'dbLogs']
)->name('logs.db');


/*
|--------------------------------------------------------------------------
| NEW FEATURE 1
| Log Analytics Dashboard
|--------------------------------------------------------------------------
*/

Route::get(
    '/logs/dashboard',
    [LogViewerController::class, 'dashboard']
)->name('logs.dashboard');


/*
|--------------------------------------------------------------------------
| NEW FEATURE 2
| Log Management
|--------------------------------------------------------------------------
*/

Route::get(
    '/logs/management',
    [LogViewerController::class, 'management']
)->name('logs.management');


/*
|--------------------------------------------------------------------------
| Clear Database Logs
|--------------------------------------------------------------------------
*/

Route::post(
    '/logs/management/clear-database',
    [LogViewerController::class, 'clearDatabaseLogs']
)->name('logs.clear.database');


/*
|--------------------------------------------------------------------------
| Clear File Logs
|--------------------------------------------------------------------------
*/

Route::post(
    '/logs/management/clear-file',
    [LogViewerController::class, 'clearFileLogs']
)->name('logs.clear.file');


/*
|--------------------------------------------------------------------------
| NEW FEATURE 3
| Cleanup Old Logs
|--------------------------------------------------------------------------
*/

Route::post(
    '/logs/management/cleanup',
    [LogViewerController::class, 'cleanupOldLogs']
)->name('logs.cleanup');
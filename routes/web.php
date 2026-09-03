<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\LogViewerController;

// ─── Original Test Routes ────────────────────────────────────────────────────

Route::get('/test-log', function () {
    Log::channel('custom')->info('Custom Log: Info message');
    Log::channel('custom')->warning('Custom Log: Warning message');
    Log::channel('custom')->error('Custom Log: Error message');

    return "Custom logs written successfully!";
});

Route::get('/file-test', function () {
    file_put_contents(
        storage_path('logs/custom.log'),
        "This is a manual test log.\n",
        FILE_APPEND
    );
    return "Manual LOG WRITTEN — check storage/logs/custom.log";
});

// ─── Feature 2: Log with Context Data ────────────────────────────────────────

Route::get('/test-context-log', function () {
    Log::channel('custom')->info('User Login Event', [
        'user_id' => 1,
        'ip'      => request()->ip(),
        'url'     => request()->fullUrl(),
        'agent'   => request()->userAgent(),
    ]);

    Log::channel('custom')->warning('Low Stock Warning', [
        'product_id' => 42,
        'stock'      => 3,
        'ip'         => request()->ip(),
    ]);

    Log::channel('custom')->error('Payment Failed', [
        'user_id'    => 1,
        'amount'     => 999,
        'ip'         => request()->ip(),
        'error_code' => 'CARD_DECLINED',
    ]);

    // Also save to DB
    DB::table('logs')->insert([
        ['level' => 'info',    'message' => 'User Login Event',   'channel' => 'custom', 'ip' => request()->ip(), 'user_id' => 1, 'url' => request()->fullUrl(), 'method' => request()->method(), 'context' => json_encode(['user_id' => 1]), 'created_at' => now(), 'updated_at' => now()],
        ['level' => 'warning', 'message' => 'Low Stock Warning',  'channel' => 'custom', 'ip' => request()->ip(), 'user_id' => null, 'url' => request()->fullUrl(), 'method' => request()->method(), 'context' => json_encode(['product_id' => 42, 'stock' => 3]), 'created_at' => now(), 'updated_at' => now()],
        ['level' => 'error',   'message' => 'Payment Failed',     'channel' => 'custom', 'ip' => request()->ip(), 'user_id' => 1, 'url' => request()->fullUrl(), 'method' => request()->method(), 'context' => json_encode(['amount' => 999, 'error_code' => 'CARD_DECLINED']), 'created_at' => now(), 'updated_at' => now()],
    ]);

    return "Context logs written to file + database!";
});

// ─── Feature 5: Daily Rotating Log ───────────────────────────────────────────

Route::get('/test-daily-log', function () {
    Log::channel('custom_daily')->info('Daily Log: Info message');
    Log::channel('custom_daily')->warning('Daily Log: Warning message');
    Log::channel('custom_daily')->error('Daily Log: Error message');

    return "Daily rotating logs written to storage/logs/custom/";
});

// ─── Feature 1 & 6: Log Viewer UI + Level Filter Routes ──────────────────────

Route::get('/log-viewer',          [LogViewerController::class, 'index'])->name('log.viewer');
Route::get('/logs/{level}',        [LogViewerController::class, 'filterByLevel'])->name('logs.level')
    ->where('level', 'info|warning|error|debug|critical');

// ─── Feature 10: Log Download Route ──────────────────────────────────────────

Route::get('/logs/download',       [LogViewerController::class, 'download'])->name('logs.download');

// ─── Feature 7: DB Logs Viewer ───────────────────────────────────────────────

Route::get('/logs/db',             [LogViewerController::class, 'dbLogs'])->name('logs.db');

<?php

use Illuminate\Support\Facades\Log;

Route::get('/test-log', function () {

    // Write an "info" type log message
    Log::channel('custom')->info('Custom Log: Info message');

    // Write a "warning" type log message
    Log::channel('custom')->warning('Custom Log: Warning message');

    // Write an "error" type log message
    Log::channel('custom')->error('Custom Log: Error message');

    return "Custom logs written successfully!";
});

Route::get('/file-test', function () {

    // Manually write text into custom.log file
    file_put_contents(
        storage_path('logs/custom.log'),
        "This is a manual test log.\n",
        FILE_APPEND
    );

    return "Manual LOG WRITTEN — check storage/logs/custom.log";
});
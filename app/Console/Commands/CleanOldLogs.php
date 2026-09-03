<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CleanOldLogs extends Command
{
    protected $signature   = 'log:clean {--days=30 : Delete logs older than this many days}';
    protected $description = 'Delete logs older than specified days (default: 30)';

    public function handle(): void
    {
        $days    = (int) $this->option('days');
        $cutoff  = Carbon::now()->subDays($days);

        // Clean database logs
        $deleted = DB::table('logs')->where('created_at', '<', $cutoff)->delete();
        $this->info("Deleted {$deleted} database log(s) older than {$days} days.");

        // Clean daily log files
        $logPath = storage_path('logs/custom');
        if (is_dir($logPath)) {
            $files = glob($logPath . '/*.log');
            $filesDeleted = 0;
            foreach ($files as $file) {
                if (filemtime($file) < $cutoff->timestamp) {
                    unlink($file);
                    $filesDeleted++;
                }
            }
            $this->info("Deleted {$filesDeleted} old daily log file(s).");
        }

        $this->info('Log cleanup complete.');
    }
}

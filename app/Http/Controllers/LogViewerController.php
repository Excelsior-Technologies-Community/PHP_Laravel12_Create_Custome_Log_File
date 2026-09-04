<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class LogViewerController extends Controller
{
    private string $logFile;

    public function __construct()
    {
        $this->logFile = storage_path('logs/custom.log');
    }

    /**
     * File log viewer
     */
    public function index(Request $request)
    {
        $filter = $request->query('level', 'all');
        $search = trim($request->query('search', ''));

        $logs = $this->parseLogs($filter, $search);

        return view('log-viewer', compact(
            'logs',
            'filter',
            'search'
        ));
    }

    /**
     * Filter file logs by level
     */
    public function filterByLevel(Request $request, string $level)
    {
        $search = trim($request->query('search', ''));

        $logs = $this->parseLogs($level, $search);

        return view('log-viewer', [
            'logs'   => $logs,
            'filter' => $level,
            'search' => $search,
        ]);
    }

    /**
     * Download custom log file
     */
    public function download()
    {
        if (!file_exists($this->logFile)) {
            abort(404, 'Log file not found.');
        }

        return response()->download(
            $this->logFile,
            'custom.log'
        );
    }

    /**
     * Database logs with advanced filters.
     */
    public function dbLogs(Request $request)
    {
        $level = $request->query('level', 'all');
        $search = trim($request->query('search', ''));
        $method = strtoupper(trim($request->query('method', 'all')));
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        $query = DB::table('logs')
            ->orderByDesc('created_at');

        if ($level !== 'all') {
            $query->where('level', strtolower($level));
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('message', 'like', "%{$search}%")
                    ->orWhere('url', 'like', "%{$search}%")
                    ->orWhere('ip', 'like', "%{$search}%");
            });
        }

        if ($method !== 'ALL') {
            $query->where('method', $method);
        }

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $logs = $query
            ->paginate(20)
            ->withQueryString();

        return view('db-logs', compact(
            'logs',
            'level',
            'search',
            'method',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Analytics dashboard.
     */
    public function dashboard()
    {
        $total = DB::table('logs')->count();

        $info = DB::table('logs')
            ->where('level', 'info')
            ->count();

        $warning = DB::table('logs')
            ->where('level', 'warning')
            ->count();

        $error = DB::table('logs')
            ->where('level', 'error')
            ->count();

        $debug = DB::table('logs')
            ->where('level', 'debug')
            ->count();

        $critical = DB::table('logs')
            ->where('level', 'critical')
            ->count();

        $today = DB::table('logs')
            ->whereDate('created_at', today())
            ->count();

        $lastSevenDays = DB::table('logs')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        $lastThirtyDays = DB::table('logs')
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        $uniqueIps = DB::table('logs')
            ->whereNotNull('ip')
            ->distinct('ip')
            ->count('ip');

        $mostUsedLevel = DB::table('logs')
            ->select('level', DB::raw('COUNT(*) as total'))
            ->groupBy('level')
            ->orderByDesc('total')
            ->first();

        $httpMethods = DB::table('logs')
            ->select(
                'method',
                DB::raw('COUNT(*) as total')
            )
            ->whereNotNull('method')
            ->groupBy('method')
            ->orderByDesc('total')
            ->get();

        $recentErrors = DB::table('logs')
            ->whereIn('level', ['error', 'critical'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $dailyStats = DB::table('logs')
            ->select(
                DB::raw('DATE(created_at) as log_date'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('log_date')
            ->get();

        return view('log-dashboard', compact(
            'total',
            'info',
            'warning',
            'error',
            'debug',
            'critical',
            'today',
            'lastSevenDays',
            'lastThirtyDays',
            'uniqueIps',
            'mostUsedLevel',
            'httpMethods',
            'recentErrors',
            'dailyStats'
        ));
    }

    /**
     * Log management page.
     */
    public function management()
    {
        $dbCount = DB::table('logs')->count();

        $fileExists = file_exists($this->logFile);

        $fileSize = $fileExists
            ? filesize($this->logFile)
            : 0;

        $dailyLogPath = storage_path('logs/custom');

        $dailyFiles = 0;

        if (is_dir($dailyLogPath)) {
            $dailyFiles = count(
                glob($dailyLogPath . '/*.log')
            );
        }

        return view('log-management', compact(
            'dbCount',
            'fileExists',
            'fileSize',
            'dailyFiles'
        ));
    }

    /**
     * Clear database logs.
     */
    public function clearDatabaseLogs()
    {
        $deleted = DB::table('logs')->delete();

        return redirect()
            ->route('logs.management')
            ->with(
                'success',
                "{$deleted} database log(s) deleted successfully."
            );
    }

    /**
     * Clear main custom.log file.
     */
    public function clearFileLogs()
    {
        if (file_exists($this->logFile)) {
            file_put_contents($this->logFile, '');
        }

        return redirect()
            ->route('logs.management')
            ->with(
                'success',
                'Custom log file cleared successfully.'
            );
    }

    /**
     * Clear old logs using existing Artisan command.
     */
    public function cleanupOldLogs(Request $request)
    {
        $days = (int) $request->input('days', 30);

        if ($days < 1) {
            return back()->with(
                'error',
                'Days must be at least 1.'
            );
        }

        Artisan::call('log:clean', [
            '--days' => $days,
        ]);

        $output = trim(
            Artisan::output()
        );

        return redirect()
            ->route('logs.management')
            ->with(
                'success',
                $output ?: 'Old logs cleaned successfully.'
            );
    }

    /**
     * Parse custom.log.
     */
    private function parseLogs(
        string $filter = 'all',
        string $search = ''
    ): array {
        if (!file_exists($this->logFile)) {
            return [];
        }

        $lines = file(
            $this->logFile,
            FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
        );

        $logs = [];

        foreach (array_reverse($lines) as $line) {

            if (!preg_match(
                '/^\[(\d{4}-\d{2}-\d{2}[T ]\d{2}:\d{2}:\d{2}[^\]]*)\]\s+\w+\.(\w+):\s+(.+)$/',
                $line,
                $matches
            )) {
                continue;
            }

            $level = strtolower($matches[2]);
            $message = $matches[3];

            if (
                $filter !== 'all' &&
                $level !== strtolower($filter)
            ) {
                continue;
            }

            if (
                $search !== '' &&
                stripos($message, $search) === false
            ) {
                continue;
            }

            $logs[] = [
                'datetime' => $matches[1],
                'level' => $level,
                'message' => $message,
            ];
        }

        return $logs;
    }
}
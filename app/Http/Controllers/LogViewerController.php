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

    /*
    |--------------------------------------------------------------------------
    | FILE LOG VIEWER
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $filter = strtolower($request->query('level', 'all'));
        $search = trim($request->query('search', ''));

        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        $perPage = 10;

        $logs = $this->parseLogs(
            $filter,
            $search,
            $dateFrom,
            $dateTo
        );

        $total = count($logs);

        $page = max(
            1,
            (int) $request->query('page', 1)
        );

        $offset = ($page - 1) * $perPage;

        $paginatedLogs = array_slice(
            $logs,
            $offset,
            $perPage
        );

        $pagination = [
            'current_page' => $page,
            'last_page' => max(
                1,
                (int) ceil($total / $perPage)
            ),
            'total' => $total,
            'per_page' => $perPage,
        ];

        return view('log-viewer', compact(
            'paginatedLogs',
            'filter',
            'search',
            'dateFrom',
            'dateTo',
            'pagination'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | FILTER FILE LOGS
    |--------------------------------------------------------------------------
    */

    public function filterByLevel(Request $request, string $level)
    {
        $request->merge([
            'level' => $level
        ]);

        return $this->index($request);
    }

    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD CUSTOM LOG
    |--------------------------------------------------------------------------
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

    /*
    |--------------------------------------------------------------------------
    | DATABASE LOGS
    |--------------------------------------------------------------------------
    */

    public function dbLogs(Request $request)
    {
        $level = strtolower(
            $request->query('level', 'all')
        );

        $search = trim(
            $request->query('search', '')
        );

        $method = strtoupper(
            trim($request->query('method', 'all'))
        );

        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        $sort = $request->query(
            'sort',
            'created_at'
        );

        $direction = strtolower(
            $request->query('direction', 'desc')
        );

        /*
        |--------------------------------------------------------------------------
        | Allowed sorting columns
        |--------------------------------------------------------------------------
        */

        $allowedSorts = [
            'id',
            'level',
            'method',
            'ip',
            'created_at',
        ];

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        $query = DB::table('logs');

        /*
        |--------------------------------------------------------------------------
        | Level Filter
        |--------------------------------------------------------------------------
        */

        if ($level !== 'all') {
            $query->where(
                'level',
                $level
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {
            $query->where(function ($q) use ($search) {

                $q->where(
                    'message',
                    'like',
                    "%{$search}%"
                )
                    ->orWhere(
                        'url',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'ip',
                        'like',
                        "%{$search}%"
                    );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | HTTP Method
        |--------------------------------------------------------------------------
        */

        if ($method !== 'ALL') {
            $query->where(
                'method',
                $method
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date From
        |--------------------------------------------------------------------------
        */

        if ($dateFrom) {
            $query->whereDate(
                'created_at',
                '>=',
                $dateFrom
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date To
        |--------------------------------------------------------------------------
        */

        if ($dateTo) {
            $query->whereDate(
                'created_at',
                '<=',
                $dateTo
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $query->orderBy(
            $sort,
            $direction
        );

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $logs = $query
            ->paginate(10)
            ->withQueryString();

        return view('db-logs', compact(
            'logs',
            'level',
            'search',
            'method',
            'dateFrom',
            'dateTo',
            'sort',
            'direction'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | CSV EXPORT
    |--------------------------------------------------------------------------
    */

    public function exportCsv(Request $request)
    {
        $level = strtolower(
            $request->query('level', 'all')
        );

        $search = trim(
            $request->query('search', '')
        );

        $method = strtoupper(
            trim($request->query('method', 'all'))
        );

        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        $query = DB::table('logs')
            ->orderByDesc('created_at');

        if ($level !== 'all') {
            $query->where(
                'level',
                $level
            );
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {

                $q->where(
                    'message',
                    'like',
                    "%{$search}%"
                )
                    ->orWhere(
                        'url',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'ip',
                        'like',
                        "%{$search}%"
                    );
            });
        }

        if ($method !== 'ALL') {
            $query->where(
                'method',
                $method
            );
        }

        if ($dateFrom) {
            $query->whereDate(
                'created_at',
                '>=',
                $dateFrom
            );
        }

        if ($dateTo) {
            $query->whereDate(
                'created_at',
                '<=',
                $dateTo
            );
        }

        $logs = $query->get();

        $filename =
            'database-logs-' .
            now()->format('Y-m-d-H-i-s') .
            '.csv';

        return response()->streamDownload(
            function () use ($logs) {

                $handle = fopen(
                    'php://output',
                    'w'
                );

                fputcsv($handle, [
                    'ID',
                    'Level',
                    'Channel',
                    'Message',
                    'Method',
                    'IP',
                    'User ID',
                    'URL',
                    'Context',
                    'Created At',
                ]);

                foreach ($logs as $log) {

                    fputcsv($handle, [
                        $log->id,
                        $log->level,
                        $log->channel,
                        $log->message,
                        $log->method,
                        $log->ip,
                        $log->user_id,
                        $log->url,
                        $log->context,
                        $log->created_at,
                    ]);
                }

                fclose($handle);
            },
            $filename,
            [
                'Content-Type' =>
                'text/csv',
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE INDIVIDUAL DATABASE LOG
    |--------------------------------------------------------------------------
    */

    public function deleteLog(int $id)
    {
        $deleted = DB::table('logs')
            ->where('id', $id)
            ->delete();

        if (!$deleted) {

            return back()->with(
                'error',
                'Log not found.'
            );
        }

        return back()->with(
            'success',
            'Log deleted successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LOG DETAILS
    |--------------------------------------------------------------------------
    */

    public function details(int $id)
    {
        $log = DB::table('logs')
            ->where('id', $id)
            ->first();

        if (!$log) {
            abort(
                404,
                'Log not found.'
            );
        }

        return view(
            'log-details',
            compact('log')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ANALYTICS DASHBOARD
    |--------------------------------------------------------------------------
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
            ->whereDate(
                'created_at',
                today()
            )
            ->count();

        $lastSevenDays = DB::table('logs')
            ->where(
                'created_at',
                '>=',
                now()->subDays(7)
            )
            ->count();

        $lastThirtyDays = DB::table('logs')
            ->where(
                'created_at',
                '>=',
                now()->subDays(30)
            )
            ->count();

        $uniqueIps = DB::table('logs')
            ->whereNotNull('ip')
            ->distinct('ip')
            ->count('ip');

        $mostUsedLevel = DB::table('logs')
            ->select(
                'level',
                DB::raw(
                    'COUNT(*) as total'
                )
            )
            ->groupBy('level')
            ->orderByDesc('total')
            ->first();

        $httpMethods = DB::table('logs')
            ->select(
                'method',
                DB::raw(
                    'COUNT(*) as total'
                )
            )
            ->whereNotNull('method')
            ->groupBy('method')
            ->orderByDesc('total')
            ->get();

        $recentErrors = DB::table('logs')
            ->whereIn(
                'level',
                [
                    'error',
                    'critical'
                ]
            )
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $dailyStats = DB::table('logs')
            ->select(
                DB::raw(
                    'DATE(created_at) as log_date'
                ),
                DB::raw(
                    'COUNT(*) as total'
                )
            )
            ->where(
                'created_at',
                '>=',
                now()
                    ->subDays(6)
                    ->startOfDay()
            )
            ->groupBy(
                DB::raw(
                    'DATE(created_at)'
                )
            )
            ->orderBy('log_date')
            ->get();

        return view(
            'log-dashboard',
            compact(
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
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MANAGEMENT
    |--------------------------------------------------------------------------
    */

    public function management()
    {
        $dbCount =
            DB::table('logs')->count();

        $fileExists =
            file_exists($this->logFile);

        $fileSize = $fileExists
            ? filesize($this->logFile)
            : 0;

        $dailyLogPath =
            storage_path('logs/custom');

        $dailyFiles = 0;

        if (is_dir($dailyLogPath)) {

            $dailyFiles = count(
                glob(
                    $dailyLogPath . '/*.log'
                )
            );
        }

        return view(
            'log-management',
            compact(
                'dbCount',
                'fileExists',
                'fileSize',
                'dailyFiles'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CLEAR DATABASE LOGS
    |--------------------------------------------------------------------------
    */

    public function clearDatabaseLogs()
    {
        $deleted =
            DB::table('logs')->delete();

        return redirect()
            ->route('logs.management')
            ->with(
                'success',
                "{$deleted} database log(s) deleted successfully."
            );
    }

    /*
    |--------------------------------------------------------------------------
    | CLEAR FILE LOGS
    |--------------------------------------------------------------------------
    */

    public function clearFileLogs()
    {
        if (file_exists($this->logFile)) {

            file_put_contents(
                $this->logFile,
                ''
            );
        }

        return redirect()
            ->route('logs.management')
            ->with(
                'success',
                'Custom log file cleared successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | CLEAN OLD LOGS
    |--------------------------------------------------------------------------
    */

    public function cleanupOldLogs(
        Request $request
    ) {
        $days = (int)
        $request->input(
            'days',
            30
        );

        if ($days < 1) {

            return back()->with(
                'error',
                'Days must be at least 1.'
            );
        }

        Artisan::call(
            'log:clean',
            [
                '--days' => $days,
            ]
        );

        $output = trim(
            Artisan::output()
        );

        return redirect()
            ->route('logs.management')
            ->with(
                'success',
                $output
                    ?: 'Old logs cleaned successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | PARSE FILE LOGS
    |--------------------------------------------------------------------------
    */

    private function parseLogs(
        string $filter = 'all',
        string $search = '',
        ?string $dateFrom = null,
        ?string $dateTo = null
    ): array {

        if (!file_exists($this->logFile)) {
            return [];
        }

        $lines = file(
            $this->logFile,
            FILE_IGNORE_NEW_LINES |
                FILE_SKIP_EMPTY_LINES
        );

        $logs = [];

        foreach (
            array_reverse($lines)
            as $line
        ) {

            if (!preg_match(
                '/^\[(\d{4}-\d{2}-\d{2}[T ]\d{2}:\d{2}:\d{2}[^\]]*)\]\s+\w+\.(\w+):\s+(.+)$/',
                $line,
                $matches
            )) {
                continue;
            }

            $datetime = $matches[1];

            $level = strtolower(
                $matches[2]
            );

            $message = $matches[3];

            /*
            |--------------------------------------------------------------------------
            | Level Filter
            |--------------------------------------------------------------------------
            */

            if (
                $filter !== 'all' &&
                $level !== strtolower($filter)
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Search
            |--------------------------------------------------------------------------
            */

            if (
                $search !== '' &&
                stripos(
                    $message,
                    $search
                ) === false
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Date From
            |--------------------------------------------------------------------------
            */

            $logDate = substr(
                $datetime,
                0,
                10
            );

            if (
                $dateFrom &&
                $logDate < $dateFrom
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Date To
            |--------------------------------------------------------------------------
            */

            if (
                $dateTo &&
                $logDate > $dateTo
            ) {
                continue;
            }

            $logs[] = [
                'datetime' => $datetime,
                'level' => $level,
                'message' => $message,
            ];
        }

        return $logs;
    }
}

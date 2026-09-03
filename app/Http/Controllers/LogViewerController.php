<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogViewerController extends Controller
{
    private string $logFile;

    public function __construct()
    {
        $this->logFile = storage_path('logs/custom.log');
    }

    public function index(Request $request)
    {
        $filter = $request->query('level', 'all');
        $logs   = $this->parseLogs($filter);

        return view('log-viewer', compact('logs', 'filter'));
    }

    public function filterByLevel(string $level)
    {
        $logs = $this->parseLogs($level);
        return view('log-viewer', compact('logs', 'level'))->with('filter', $level);
    }

    public function download()
    {
        if (!file_exists($this->logFile)) {
            abort(404, 'Log file not found.');
        }
        return response()->download($this->logFile, 'custom.log');
    }

    public function dbLogs(Request $request)
    {
        $level = $request->query('level', 'all');
        $query = DB::table('logs')->orderByDesc('created_at');

        if ($level !== 'all') {
            $query->where('level', $level);
        }

        $logs = $query->paginate(20);
        return view('db-logs', compact('logs', 'level'));
    }

    private function parseLogs(string $filter = 'all'): array
    {
        if (!file_exists($this->logFile)) {
            return [];
        }

        $lines  = file($this->logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $logs   = [];

        foreach (array_reverse($lines) as $line) {
            if (!preg_match('/^\[(\d{4}-\d{2}-\d{2}[T ]\d{2}:\d{2}:\d{2}[^\]]*)\]\s+\w+\.(\w+):\s+(.+)$/', $line, $matches)) {
                continue;
            }

            $level = strtolower($matches[2]);

            if ($filter !== 'all' && $level !== strtolower($filter)) {
                continue;
            }

            $logs[] = [
                'datetime' => $matches[1],
                'level'    => $level,
                'message'  => $matches[3],
            ];
        }

        return $logs;
    }
}

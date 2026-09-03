<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Log Viewer</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; color: #333; }

        .header {
            background: #1e293b;
            color: white;
            padding: 16px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .header h1 { font-size: 1.4rem; }
        .header a {
            background: #3b82f6;
            color: white;
            padding: 7px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.85rem;
        }

        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }

        .filter-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            align-items: center;
        }
        .filter-bar span { font-weight: 600; color: #555; }
        .btn {
            padding: 7px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            border: 2px solid transparent;
            transition: all 0.2s;
        }
        .btn-all    { background: #1e293b; color: white; }
        .btn-info   { background: #0ea5e9; color: white; }
        .btn-warning{ background: #f59e0b; color: white; }
        .btn-error  { background: #ef4444; color: white; }
        .btn-active { border-color: #fff; box-shadow: 0 0 0 3px rgba(0,0,0,0.2); }
        .btn-download { background: #10b981; color: white; margin-left: auto; }
        .btn-db     { background: #8b5cf6; color: white; }

        .stats {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 15px 25px;
            flex: 1;
            min-width: 140px;
            text-align: center;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }
        .stat-card .num { font-size: 2rem; font-weight: 700; }
        .stat-card .lbl { font-size: 0.8rem; color: #888; margin-top: 4px; }
        .num-all     { color: #1e293b; }
        .num-info    { color: #0ea5e9; }
        .num-warning { color: #f59e0b; }
        .num-error   { color: #ef4444; }

        table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.08); }
        thead { background: #1e293b; color: white; }
        th, td { padding: 12px 16px; text-align: left; font-size: 0.88rem; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr:hover { background: #e0f2fe; }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge-info    { background: #dbeafe; color: #1d4ed8; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-error   { background: #fee2e2; color: #991b1b; }
        .badge-debug   { background: #f3f4f6; color: #374151; }
        .badge-critical{ background: #7c3aed; color: white; }

        .empty { text-align: center; padding: 50px; color: #aaa; font-size: 1.1rem; }
        .msg-cell { max-width: 600px; word-break: break-word; }
    </style>
</head>
<body>

<div class="header">
    <h1>📋 Custom Log Viewer</h1>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('logs.db') }}">🗄 DB Logs</a>
        <a href="{{ route('logs.download') }}">⬇ Download Log</a>
    </div>
</div>

<div class="container">

    {{-- Stats --}}
    @php
        $allLogs  = collect($logs);
        $infoCnt  = $allLogs->where('level','info')->count();
        $warnCnt  = $allLogs->where('level','warning')->count();
        $errCnt   = $allLogs->where('level','error')->count();
    @endphp

    <div class="stats">
        <div class="stat-card"><div class="num num-all">{{ count($logs) }}</div><div class="lbl">Showing</div></div>
        <div class="stat-card"><div class="num num-info">{{ $infoCnt }}</div><div class="lbl">Info</div></div>
        <div class="stat-card"><div class="num num-warning">{{ $warnCnt }}</div><div class="lbl">Warning</div></div>
        <div class="stat-card"><div class="num num-error">{{ $errCnt }}</div><div class="lbl">Error</div></div>
    </div>

    {{-- Filter Buttons --}}
    <div class="filter-bar">
        <span>Filter:</span>
        <a href="{{ route('log.viewer') }}" class="btn btn-all {{ $filter === 'all' ? 'btn-active' : '' }}">All</a>
        <a href="{{ route('logs.level', 'info') }}" class="btn btn-info {{ $filter === 'info' ? 'btn-active' : '' }}">Info</a>
        <a href="{{ route('logs.level', 'warning') }}" class="btn btn-warning {{ $filter === 'warning' ? 'btn-active' : '' }}">Warning</a>
        <a href="{{ route('logs.level', 'error') }}" class="btn btn-error {{ $filter === 'error' ? 'btn-active' : '' }}">Error</a>
    </div>

    {{-- Table --}}
    @if(count($logs) > 0)
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Date & Time</th>
                <th>Level</th>
                <th>Message</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $i => $log)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $log['datetime'] }}</td>
                <td>
                    <span class="badge badge-{{ $log['level'] }}">{{ $log['level'] }}</span>
                </td>
                <td class="msg-cell">{{ $log['message'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <div class="empty">No logs found for filter: <strong>{{ $filter }}</strong></div>
    @endif

</div>
</body>
</html>

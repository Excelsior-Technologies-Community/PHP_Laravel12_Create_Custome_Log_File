<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Logs</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; color: #333; }
        .header { background: #1e293b; color: white; padding: 16px 30px; display: flex; align-items: center; justify-content: space-between; }
        .header h1 { font-size: 1.4rem; }
        .header a { background: #3b82f6; color: white; padding: 7px 16px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .filter-bar { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; align-items: center; }
        .filter-bar span { font-weight: 600; color: #555; }
        .btn { padding: 7px 18px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 500; border: 2px solid transparent; }
        .btn-all     { background: #1e293b; color: white; }
        .btn-info    { background: #0ea5e9; color: white; }
        .btn-warning { background: #f59e0b; color: white; }
        .btn-error   { background: #ef4444; color: white; }
        .btn-active  { box-shadow: 0 0 0 3px rgba(0,0,0,0.2); }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.08); }
        thead { background: #1e293b; color: white; }
        th, td { padding: 12px 16px; text-align: left; font-size: 0.85rem; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr:hover { background: #e0f2fe; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; }
        .badge-info    { background: #dbeafe; color: #1d4ed8; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-error   { background: #fee2e2; color: #991b1b; }
        .context-cell { max-width: 300px; font-size: 0.78rem; color: #555; word-break: break-all; }
        .msg-cell { max-width: 400px; word-break: break-word; }
        .pagination { margin-top: 20px; display: flex; gap: 8px; justify-content: center; }
        .pagination a, .pagination span { padding: 6px 14px; border-radius: 6px; background: white; border: 1px solid #ddd; text-decoration: none; font-size: 0.85rem; }
        .pagination .active span { background: #1e293b; color: white; border-color: #1e293b; }
        .empty { text-align: center; padding: 50px; color: #aaa; font-size: 1.1rem; }
    </style>
</head>
<body>

<div class="header">
    <h1>🗄 Database Logs</h1>
    <a href="{{ route('log.viewer') }}">← File Log Viewer</a>
</div>

<div class="container">

    <div class="filter-bar">
        <span>Filter:</span>
        <a href="{{ route('logs.db') }}" class="btn btn-all {{ $level === 'all' ? 'btn-active' : '' }}">All</a>
        <a href="{{ route('logs.db', ['level' => 'info']) }}" class="btn btn-info {{ $level === 'info' ? 'btn-active' : '' }}">Info</a>
        <a href="{{ route('logs.db', ['level' => 'warning']) }}" class="btn btn-warning {{ $level === 'warning' ? 'btn-active' : '' }}">Warning</a>
        <a href="{{ route('logs.db', ['level' => 'error']) }}" class="btn btn-error {{ $level === 'error' ? 'btn-active' : '' }}">Error</a>
    </div>

    @if($logs->count() > 0)
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Level</th>
                <th>Channel</th>
                <th>Message</th>
                <th>IP</th>
                <th>User ID</th>
                <th>Context</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td>{{ $log->id }}</td>
                <td><span class="badge badge-{{ $log->level }}">{{ $log->level }}</span></td>
                <td>{{ $log->channel }}</td>
                <td class="msg-cell">{{ $log->message }}</td>
                <td>{{ $log->ip ?? '-' }}</td>
                <td>{{ $log->user_id ?? '-' }}</td>
                <td class="context-cell">{{ $log->context ? json_encode(json_decode($log->context), JSON_PRETTY_PRINT) : '-' }}</td>
                <td>{{ $log->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination">
        {{ $logs->appends(['level' => $level])->links() }}
    </div>
    @else
        <div class="empty">No database logs found.</div>
    @endif

</div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Log Analytics Dashboard</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        .header {
            background: #0f172a;
            color: white;
            padding: 20px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }

        .header h1 {
            font-size: 1.5rem;
        }

        .nav {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .nav a {
            background: #2563eb;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.85rem;
        }

        .container {
            max-width: 1300px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 1px 5px rgba(0,0,0,0.08);
        }

        .card h3 {
            font-size: 0.8rem;
            color: #64748b;
            margin-bottom: 8px;
        }

        .number {
            font-size: 2rem;
            font-weight: 700;
        }

        .info {
            color: #0284c7;
        }

        .warning {
            color: #d97706;
        }

        .error {
            color: #dc2626;
        }

        .critical {
            color: #7c3aed;
        }

        .section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 1px 5px rgba(0,0,0,0.08);
        }

        .section h2 {
            font-size: 1.05rem;
            margin-bottom: 15px;
        }

        .quick-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .quick {
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
        }

        .quick strong {
            display: block;
            font-size: 1.4rem;
            margin-bottom: 5px;
        }

        .quick span {
            color: #64748b;
            font-size: 0.8rem;
        }

        .bar-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 12px 0;
        }

        .bar-label {
            width: 100px;
            font-size: 0.85rem;
        }

        .bar {
            flex: 1;
            background: #e2e8f0;
            height: 12px;
            border-radius: 10px;
            overflow: hidden;
        }

        .bar-fill {
            height: 100%;
            background: #2563eb;
        }

        .bar-count {
            width: 50px;
            text-align: right;
            font-size: 0.8rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 11px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
            font-size: 0.82rem;
        }

        th {
            background: #f8fafc;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .badge-error {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-critical {
            background: #ede9fe;
            color: #6d28d9;
        }

        .empty {
            text-align: center;
            color: #94a3b8;
            padding: 20px;
        }

        @media(max-width: 900px) {

            .grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .quick-grid {
                grid-template-columns: 1fr;
            }

        }

        @media(max-width: 550px) {

            .grid {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 0 10px;
            }

            table {
                display: block;
                overflow-x: auto;
            }

        }

    </style>

</head>

<body>

<div class="header">

    <h1>📊 Log Analytics Dashboard</h1>

    <div class="nav">

        <a href="{{ route('logs.db') }}">
            🗄 DB Logs
        </a>

        <a href="{{ route('log.viewer') }}">
            📋 File Logs
        </a>

        <a href="{{ route('logs.management') }}">
            🧹 Management
        </a>

    </div>

</div>


<div class="container">

    {{-- Main Statistics --}}

    <div class="grid">

        <div class="card">

            <h3>Total Logs</h3>

            <div class="number">
                {{ $total }}
            </div>

        </div>


        <div class="card">

            <h3>Info Logs</h3>

            <div class="number info">
                {{ $info }}
            </div>

        </div>


        <div class="card">

            <h3>Warning Logs</h3>

            <div class="number warning">
                {{ $warning }}
            </div>

        </div>


        <div class="card">

            <h3>Error Logs</h3>

            <div class="number error">
                {{ $error }}
            </div>

        </div>


        <div class="card">

            <h3>Debug Logs</h3>

            <div class="number">
                {{ $debug }}
            </div>

        </div>


        <div class="card">

            <h3>Critical Logs</h3>

            <div class="number critical">
                {{ $critical }}
            </div>

        </div>


        <div class="card">

            <h3>Today's Logs</h3>

            <div class="number">
                {{ $today }}
            </div>

        </div>


        <div class="card">

            <h3>Unique IPs</h3>

            <div class="number">
                {{ $uniqueIps }}
            </div>

        </div>

    </div>


    {{-- Period Statistics --}}

    <div class="section">

        <h2>📅 Log Activity</h2>

        <div class="quick-grid">

            <div class="quick">

                <strong>
                    {{ $today }}
                </strong>

                <span>
                    Logs Today
                </span>

            </div>


            <div class="quick">

                <strong>
                    {{ $lastSevenDays }}
                </strong>

                <span>
                    Last 7 Days
                </span>

            </div>


            <div class="quick">

                <strong>
                    {{ $lastThirtyDays }}
                </strong>

                <span>
                    Last 30 Days
                </span>

            </div>

        </div>

    </div>


    {{-- Most Used Level --}}

    <div class="section">

        <h2>🏆 Most Used Log Level</h2>

        @if($mostUsedLevel)

            <div class="quick">

                <strong>
                    {{ strtoupper($mostUsedLevel->level) }}
                </strong>

                <span>
                    {{ $mostUsedLevel->total }} logs
                </span>

            </div>

        @else

            <div class="empty">
                No log data available.
            </div>

        @endif

    </div>


    {{-- HTTP Methods --}}

    <div class="section">

        <h2>🌐 HTTP Request Statistics</h2>

        @if($httpMethods->count())

            @php
                $maxMethodCount = max(
                    1,
                    $httpMethods->max('total')
                );
            @endphp

            @foreach($httpMethods as $method)

                <div class="bar-row">

                    <div class="bar-label">
                        {{ $method->method }}
                    </div>

                    <div class="bar">

                        <div
                            class="bar-fill"
                            style="width: {{ ($method->total / $maxMethodCount) * 100 }}%"
                        ></div>

                    </div>

                    <div class="bar-count">
                        {{ $method->total }}
                    </div>

                </div>

            @endforeach

        @else

            <div class="empty">
                No HTTP request logs available.
            </div>

        @endif

    </div>


    {{-- Recent Errors --}}

    <div class="section">

        <h2>🚨 Recent Errors</h2>

        @if($recentErrors->count())

            <table>

                <thead>

                <tr>
                    <th>Level</th>
                    <th>Message</th>
                    <th>URL</th>
                    <th>IP</th>
                    <th>Date</th>
                </tr>

                </thead>

                <tbody>

                @foreach($recentErrors as $log)

                    <tr>

                        <td>

                            <span class="badge badge-{{ $log->level }}">
                                {{ strtoupper($log->level) }}
                            </span>

                        </td>

                        <td>
                            {{ $log->message }}
                        </td>

                        <td>
                            {{ $log->url ?? '-' }}
                        </td>

                        <td>
                            {{ $log->ip ?? '-' }}
                        </td>

                        <td>
                            {{ $log->created_at }}
                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        @else

            <div class="empty">
                🎉 No errors or critical logs found.
            </div>

        @endif

    </div>


    {{-- Last 7 Days --}}

    <div class="section">

        <h2>📈 Last 7 Days</h2>

        @if($dailyStats->count())

            @php
                $maxDaily = max(
                    1,
                    $dailyStats->max('total')
                );
            @endphp

            @foreach($dailyStats as $day)

                <div class="bar-row">

                    <div class="bar-label">
                        {{ $day->log_date }}
                    </div>

                    <div class="bar">

                        <div
                            class="bar-fill"
                            style="width: {{ ($day->total / $maxDaily) * 100 }}%"
                        ></div>

                    </div>

                    <div class="bar-count">
                        {{ $day->total }}
                    </div>

                </div>

            @endforeach

        @else

            <div class="empty">
                No activity available.
            </div>

        @endif

    </div>

</div>

</body>

</html>
<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Custom Log Viewer</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;

            font-family:
                Arial,
                sans-serif;

            background: #f1f5f9;

            color: #1e293b;
        }

        .header {
            background: #0f172a;

            color: white;

            padding: 18px 30px;

            display: flex;

            justify-content:
                space-between;

            align-items: center;

            gap: 10px;

            flex-wrap: wrap;
        }

        .header h1 {
            font-size: 1.4rem;
        }

        .nav {
            display: flex;

            gap: 8px;

            flex-wrap: wrap;
        }

        .nav a {
            background: #2563eb;

            color: white;

            padding: 8px 13px;

            border-radius: 6px;

            text-decoration: none;

            font-size: .82rem;
        }

        .container {
            max-width: 1250px;

            margin: 30px auto;

            padding: 0 20px;
        }

        .filter-box {
            background: white;

            padding: 20px;

            border-radius: 10px;

            margin-bottom: 20px;

            box-shadow:
                0 1px 5px rgba(0, 0, 0, .08);
        }

        .filters {
            display: grid;

            grid-template-columns:
                2fr 1fr 1fr auto;

            gap: 10px;

            align-items: end;
        }

        label {
            display: block;

            font-size: .75rem;

            font-weight: bold;

            margin-bottom: 5px;
        }

        input,
        select {
            width: 100%;

            padding: 9px;

            border:
                1px solid #d1d5db;

            border-radius: 6px;
        }

        .btn {
            display: inline-block;

            padding: 9px 14px;

            border-radius: 6px;

            border: none;

            text-decoration: none;

            cursor: pointer;

            font-size: .82rem;
        }

        .search {
            background: #2563eb;
            color: white;
        }

        .clear {
            background: #64748b;
            color: white;
        }

        .download {
            background: #16a34a;
            color: white;
        }

        .filters-level {
            display: flex;

            gap: 7px;

            margin-bottom: 20px;

            flex-wrap: wrap;
        }

        .filters-level a {
            padding: 7px 14px;

            border-radius: 20px;

            text-decoration: none;

            font-size: .8rem;

            color: white;
        }

        .all {
            background: #334155;
        }

        .info {
            background: #0284c7;
        }

        .warning {
            background: #d97706;
        }

        .error {
            background: #dc2626;
        }

        .active {
            outline: 3px solid #cbd5e1;
        }

        .stats {
            display: grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap: 15px;

            margin-bottom: 20px;
        }

        .stat {
            background: white;

            padding: 18px;

            border-radius: 10px;

            text-align: center;

            box-shadow:
                0 1px 5px rgba(0, 0, 0, .08);
        }

        .stat strong {
            display: block;

            font-size: 1.8rem;
        }

        .stat span {
            color: #64748b;

            font-size: .8rem;
        }

        table {
            width: 100%;

            border-collapse:
                collapse;

            background: white;

            border-radius: 10px;

            overflow: hidden;

            box-shadow:
                0 1px 5px rgba(0, 0, 0, .08);
        }

        th {
            background: #1e293b;

            color: white;

            padding: 12px;

            text-align: left;

            font-size: .82rem;
        }

        td {
            padding: 12px;

            border-bottom:
                1px solid #e5e7eb;

            font-size: .82rem;
        }

        .badge {
            padding: 4px 9px;

            border-radius: 20px;

            font-size: .7rem;

            font-weight: bold;

            text-transform:
                uppercase;
        }

        .badge-info {
            background: #dbeafe;

            color: #1d4ed8;
        }

        .badge-warning {
            background: #fef3c7;

            color: #92400e;
        }

        .badge-error {
            background: #fee2e2;

            color: #991b1b;
        }

        .badge-debug {
            background: #e5e7eb;

            color: #374151;
        }

        .badge-critical {
            background: #ede9fe;

            color: #6d28d9;
        }

        .message {
            max-width: 650px;

            word-break: break-word;
        }

        .pagination {
            display: flex;

            justify-content: center;

            gap: 5px;

            margin-top: 20px;

            flex-wrap: wrap;
        }

        .pagination a,
        .pagination span {
            min-width: 36px;

            height: 36px;

            display: flex;

            align-items: center;

            justify-content: center;

            border:
                1px solid #d1d5db;

            border-radius: 6px;

            background: white;

            color: #334155;

            text-decoration: none;

            font-size: .82rem;
        }

        .pagination .current {
            background: #2563eb;

            color: white;

            border-color: #2563eb;
        }

        .empty {
            background: white;

            padding: 50px;

            text-align: center;

            border-radius: 10px;

            color: #94a3b8;
        }

        @media(max-width: 800px) {

            .filters {
                grid-template-columns: 1fr;
            }

            .stats {
                grid-template-columns:
                    repeat(2, 1fr);
            }

            table {
                min-width: 800px;
            }

        }

        @media(max-width: 500px) {

            .container {
                padding: 0 10px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

        }
    </style>

</head>

<body>

    <div class="header">

        <h1>
            📋 Custom Log Viewer
        </h1>

        <div class="nav">

            <a href="{{ route('logs.db') }}">
                🗄 DB Logs
            </a>

            <a href="{{ route('logs.dashboard') }}">
                📊 Dashboard
            </a>

            <a href="{{ route('logs.management') }}">
                🧹 Management
            </a>

            <a href="{{ route('logs.download') }}">
                ⬇ Download
            </a>

        </div>

    </div>


    <div class="container">


        {{-- Search / Date Filter --}}

        <div class="filter-box">

            <form
                method="GET"
                action="{{ route('log.viewer') }}">

                <div class="filters">

                    <div>

                        <label>
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search log message">

                    </div>


                    <div>

                        <label>
                            Date From
                        </label>

                        <input
                            type="date"
                            name="date_from"
                            value="{{ $dateFrom }}">

                    </div>


                    <div>

                        <label>
                            Date To
                        </label>

                        <input
                            type="date"
                            name="date_to"
                            value="{{ $dateTo }}">

                    </div>


                    <div>

                        <button
                            class="btn search"
                            type="submit">

                            🔎 Search

                        </button>

                        <a
                            href="{{ route('log.viewer') }}"
                            class="btn clear">

                            Clear

                        </a>

                    </div>

                </div>

            </form>

        </div>


        {{-- Level Filters --}}

        <div class="filters-level">

            <a
                class="all {{ $filter === 'all' ? 'active' : '' }}"
                href="{{ route('log.viewer') }}">

                All

            </a>

            <a
                class="info {{ $filter === 'info' ? 'active' : '' }}"
                href="{{ route('logs.level','info') }}">

                Info

            </a>

            <a
                class="warning {{ $filter === 'warning' ? 'active' : '' }}"
                href="{{ route('logs.level','warning') }}">

                Warning

            </a>

            <a
                class="error {{ $filter === 'error' ? 'active' : '' }}"
                href="{{ route('logs.level','error') }}">

                Error

            </a>

        </div>


        {{-- Statistics --}}

        @php

        $collection =
        collect($paginatedLogs);

        $info =
        $collection
        ->where('level','info')
        ->count();

        $warning =
        $collection
        ->where('level','warning')
        ->count();

        $error =
        $collection
        ->where('level','error')
        ->count();

        @endphp


        <div class="stats">

            <div class="stat">

                <strong>
                    {{ $pagination['total'] }}
                </strong>

                <span>
                    Total Matching Logs
                </span>

            </div>


            <div class="stat">

                <strong>
                    {{ $info }}
                </strong>

                <span>
                    Info On This Page
                </span>

            </div>


            <div class="stat">

                <strong>
                    {{ $warning }}
                </strong>

                <span>
                    Warning On This Page
                </span>

            </div>


            <div class="stat">

                <strong>
                    {{ $error }}
                </strong>

                <span>
                    Error On This Page
                </span>

            </div>

        </div>


        @if(count($paginatedLogs))

        <table>

            <thead>

                <tr>

                    <th>
                        #
                    </th>

                    <th>
                        Date & Time
                    </th>

                    <th>
                        Level
                    </th>

                    <th>
                        Message
                    </th>

                </tr>

            </thead>

            <tbody>

                @foreach(
                $paginatedLogs
                as $i => $log
                )

                <tr>

                    <td>
                        {{
                            (
                                (
                                    $pagination[
                                        'current_page'
                                    ] - 1
                                )
                                *
                                $pagination[
                                    'per_page'
                                ]
                            )
                            + $i
                            + 1
                        }}
                    </td>

                    <td>
                        {{ $log['datetime'] }}
                    </td>

                    <td>

                        <span
                            class="badge badge-{{ $log['level'] }}">

                            {{ $log['level'] }}

                        </span>

                    </td>

                    <td class="message">
                        {{ $log['message'] }}
                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>


        {{-- Numbers only --}}

        @if(
        $pagination['last_page'] > 1
        )

        <div class="pagination">

            @for(
            $page = 1;
            $page <= $pagination['last_page'];
                $page++
                )

                @php

                $query=request()->except('page');

                $query['page'] =
                $page;

                @endphp


                @if(
                $page ==
                $pagination['current_page']
                )

                <span class="current">
                    {{ $page }}
                </span>

                @else

                <a
                    href="{{ route(
                                'log.viewer',
                                $query
                            ) }}">

                    {{ $page }}

                </a>

                @endif

                @endfor

        </div>

        @endif

        @else

        <div class="empty">

            🔍 No logs found.

        </div>

        @endif

    </div>


    {{-- AUTO REFRESH --}}

    <script>
        /*
    |--------------------------------------------------------------------------
    | New Feature 8
    | Auto refresh every 30 seconds
    |--------------------------------------------------------------------------
    */

        setTimeout(function() {

            window.location.reload();

        }, 30000);
    </script>

</body>

</html>
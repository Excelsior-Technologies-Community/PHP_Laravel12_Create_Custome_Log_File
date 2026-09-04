<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <title>Database Logs</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            color: #333;
        }

        /* ---------------------------------------------------------
           Header
        --------------------------------------------------------- */

        .header {
            background: #1e293b;
            color: white;
            padding: 16px 30px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 15px;
            flex-wrap: wrap;
        }

        .header h1 {
            font-size: 1.4rem;
        }

        .header-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .header a {
            background: #3b82f6;
            color: white;

            padding: 8px 14px;

            border-radius: 6px;

            text-decoration: none;

            font-size: 0.85rem;

            transition: 0.2s ease;
        }

        .header a:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        /* ---------------------------------------------------------
           Container
        --------------------------------------------------------- */

        .container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
        }

        /* ---------------------------------------------------------
           Filter Panel
        --------------------------------------------------------- */

        .filter-panel {
            background: white;

            padding: 20px;

            border-radius: 10px;

            margin-bottom: 20px;

            box-shadow:
                0 1px 4px rgba(0, 0, 0, 0.08);
        }

        .filter-panel h2 {
            font-size: 1rem;
            margin-bottom: 15px;
        }

        .filters {
            display: grid;

            grid-template-columns:
                2fr 1fr 1fr 1fr 1fr auto auto;

            gap: 10px;

            align-items: end;
        }

        .field label {
            display: block;

            font-size: 0.75rem;

            font-weight: 600;

            margin-bottom: 5px;

            color: #555;
        }

        input,
        select {
            width: 100%;

            padding: 9px 10px;

            border: 1px solid #d1d5db;

            border-radius: 6px;

            font-size: 0.85rem;

            background: white;

            outline: none;

            transition: border-color 0.2s ease,
                box-shadow 0.2s ease;
        }

        input:focus,
        select:focus {
            border-color: #2563eb;

            box-shadow:
                0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* ---------------------------------------------------------
           Buttons
        --------------------------------------------------------- */

        .btn {
            padding: 9px 14px;

            border-radius: 6px;

            border: none;

            cursor: pointer;

            text-decoration: none;

            font-size: 0.85rem;

            display: inline-block;

            text-align: center;

            transition: 0.2s ease;
        }

        .btn-search {
            background: #2563eb;
            color: white;
        }

        .btn-search:hover {
            background: #1d4ed8;
        }

        .btn-clear {
            background: #64748b;
            color: white;
        }

        .btn-clear:hover {
            background: #475569;
        }

        /* ---------------------------------------------------------
           Table Wrapper
        --------------------------------------------------------- */

        .table-wrapper {
            width: 100%;

            overflow-x: auto;

            background: white;

            border-radius: 10px;

            box-shadow:
                0 1px 4px rgba(0, 0, 0, 0.08);

            -webkit-overflow-scrolling: touch;
        }

        /*
         * Keep a minimum width so every column gets enough space.
         * On smaller screens the wrapper will scroll horizontally.
         */

        table {
            width: 100%;

            min-width: 1250px;

            border-collapse: collapse;

            background: white;
        }

        /* ---------------------------------------------------------
           Table Header
        --------------------------------------------------------- */

        thead {
            background: #1e293b;
            color: white;
        }

        th {
            padding: 12px 14px;

            text-align: left;

            font-size: 0.82rem;

            font-weight: 600;

            white-space: nowrap;
        }

        td {
            padding: 12px 14px;

            text-align: left;

            font-size: 0.82rem;

            vertical-align: top;

            border-bottom: 1px solid #eef2f7;
        }

        tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        tbody tr:hover {
            background: #e0f2fe;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        /* ---------------------------------------------------------
           Column Widths
        --------------------------------------------------------- */

        th:nth-child(1),
        td:nth-child(1) {
            width: 60px;
        }

        th:nth-child(2),
        td:nth-child(2) {
            width: 100px;
        }

        th:nth-child(3),
        td:nth-child(3) {
            width: 100px;
        }

        th:nth-child(4),
        td:nth-child(4) {
            width: 250px;
        }

        th:nth-child(5),
        td:nth-child(5) {
            width: 90px;
        }

        th:nth-child(6),
        td:nth-child(6) {
            width: 120px;
        }

        th:nth-child(7),
        td:nth-child(7) {
            width: 90px;
        }

        th:nth-child(8),
        td:nth-child(8) {
            width: 250px;
        }

        th:nth-child(9),
        td:nth-child(9) {
            width: 280px;
        }

        th:nth-child(10),
        td:nth-child(10) {
            width: 160px;
        }

        /* ---------------------------------------------------------
           Badges
        --------------------------------------------------------- */

        .badge {
            display: inline-block;

            padding: 3px 10px;

            border-radius: 20px;

            font-size: 0.7rem;

            font-weight: 600;

            text-transform: uppercase;

            white-space: nowrap;
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
            background: #f3f4f6;
            color: #374151;
        }

        .badge-critical {
            background: #7c3aed;
            color: white;
        }

        /* ---------------------------------------------------------
           Message
        --------------------------------------------------------- */

        .msg-cell {
            max-width: 250px;

            word-break: break-word;

            overflow-wrap: anywhere;

            white-space: normal;

            line-height: 1.5;
        }

        /* ---------------------------------------------------------
           URL
        --------------------------------------------------------- */

        .url-cell {
            width: 250px;

            max-width: 250px;

            word-break: break-all;

            overflow-wrap: anywhere;

            white-space: normal;

            line-height: 1.5;

            color: #334155;
        }

        /* ---------------------------------------------------------
           Context
        --------------------------------------------------------- */

        .context-cell {
            width: 280px;

            max-width: 280px;

            min-width: 240px;

            vertical-align: top;

            font-size: 0.75rem;

            color: #555;

            white-space: normal;

            word-break: break-word;

            overflow-wrap: anywhere;

            line-height: 1.5;
        }

        /*
         * IMPORTANT:
         * <pre> normally prevents text wrapping.
         * These rules force JSON to wrap inside the cell.
         */

        .context-cell pre {
            margin: 0;

            padding: 8px;

            width: 100%;

            max-width: 100%;

            background: #f8fafc;

            border: 1px solid #e2e8f0;

            border-radius: 6px;

            white-space: pre-wrap;

            word-break: break-word;

            overflow-wrap: anywhere;

            overflow-x: hidden;

            font-family:
                Consolas,
                Monaco,
                'Courier New',
                monospace;

            font-size: 0.72rem;

            line-height: 1.5;
        }

        /* ---------------------------------------------------------
           Created At
        --------------------------------------------------------- */

        .created-at-cell {
            min-width: 160px;

            width: 160px;

            white-space: nowrap;

            vertical-align: top;

            color: #475569;

            font-size: 0.78rem;
        }

        /* ---------------------------------------------------------
           Pagination
        --------------------------------------------------------- */

        .pagination {
            margin-top: 20px;

            display: flex;

            justify-content: center;

            overflow-x: auto;

            padding-bottom: 5px;
        }

        /* ---------------------------------------------------------
           Empty State
        --------------------------------------------------------- */

        .empty {
            text-align: center;

            padding: 50px;

            color: #aaa;

            background: white;

            border-radius: 10px;

            box-shadow:
                0 1px 4px rgba(0, 0, 0, 0.08);
        }

        /* ---------------------------------------------------------
           Responsive
        --------------------------------------------------------- */

        @media(max-width: 1100px) {

            .filters {
                grid-template-columns: 1fr 1fr;
            }

        }

        @media(max-width: 700px) {

            .header {
                padding: 15px 20px;
            }

            .header h1 {
                font-size: 1.2rem;
            }

            .container {
                padding: 0 10px;

                margin-top: 20px;
            }

            .filter-panel {
                padding: 15px;
            }

            .filters {
                grid-template-columns: 1fr;
            }

            .header-actions {
                width: 100%;
            }

            .header-actions a {
                flex: 1;
                text-align: center;
            }

            .table-wrapper {
                border-radius: 8px;
            }

        }
    </style>

</head>

<body>

    <!-- =============================================================
     HEADER
============================================================= -->

    <div class="header">

        <h1>
            🗄 Database Logs
        </h1>

        <div class="header-actions">

            <a href="{{ route('logs.dashboard') }}">
                📊 Dashboard
            </a>

            <a href="{{ route('logs.management') }}">
                🧹 Management
            </a>

            <a href="{{ route('log.viewer') }}">
                📋 File Logs
            </a>

        </div>

    </div>


    <!-- =============================================================
     MAIN CONTAINER
============================================================= -->

    <div class="container">


        <!-- =========================================================
         FILTER PANEL
    ========================================================== -->

        <div class="filter-panel">

            <h2>
                🔎 Advanced Log Search
            </h2>

            <form
                method="GET"
                action="{{ route('logs.db') }}">

                <div class="filters">


                    <!-- Search -->

                    <div class="field">

                        <label>
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Message, URL or IP...">

                    </div>


                    <!-- Level -->

                    <div class="field">

                        <label>
                            Level
                        </label>

                        <select name="level">

                            <option
                                value="all"
                                {{ $level === 'all' ? 'selected' : '' }}>
                                All Levels
                            </option>

                            <option
                                value="info"
                                {{ $level === 'info' ? 'selected' : '' }}>
                                Info
                            </option>

                            <option
                                value="warning"
                                {{ $level === 'warning' ? 'selected' : '' }}>
                                Warning
                            </option>

                            <option
                                value="error"
                                {{ $level === 'error' ? 'selected' : '' }}>
                                Error
                            </option>

                            <option
                                value="debug"
                                {{ $level === 'debug' ? 'selected' : '' }}>
                                Debug
                            </option>

                            <option
                                value="critical"
                                {{ $level === 'critical' ? 'selected' : '' }}>
                                Critical
                            </option>

                        </select>

                    </div>


                    <!-- HTTP Method -->

                    <div class="field">

                        <label>
                            HTTP Method
                        </label>

                        <select name="method">

                            <option
                                value="all"
                                {{ $method === 'ALL' ? 'selected' : '' }}>
                                All Methods
                            </option>

                            @foreach([
                            'GET',
                            'POST',
                            'PUT',
                            'PATCH',
                            'DELETE'
                            ] as $httpMethod)

                            <option
                                value="{{ $httpMethod }}"
                                {{ $method === $httpMethod ? 'selected' : '' }}>
                                {{ $httpMethod }}
                            </option>

                            @endforeach

                        </select>

                    </div>


                    <!-- Date From -->

                    <div class="field">

                        <label>
                            Date From
                        </label>

                        <input
                            type="date"
                            name="date_from"
                            value="{{ $dateFrom }}">

                    </div>


                    <!-- Date To -->

                    <div class="field">

                        <label>
                            Date To
                        </label>

                        <input
                            type="date"
                            name="date_to"
                            value="{{ $dateTo }}">

                    </div>


                    <!-- Search Button -->

                    <button
                        type="submit"
                        class="btn btn-search">
                        🔎 Search
                    </button>


                    <!-- Clear Button -->

                    <a
                        href="{{ route('logs.db') }}"
                        class="btn btn-clear">
                        ✕ Clear
                    </a>

                </div>

            </form>

        </div>


        <!-- =========================================================
         LOG TABLE
    ========================================================== -->

        @if($logs->count() > 0)

        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>

                        <th>
                            ID
                        </th>

                        <th>
                            Level
                        </th>

                        <th>
                            Channel
                        </th>

                        <th>
                            Message
                        </th>

                        <th>
                            Method
                        </th>

                        <th>
                            IP
                        </th>

                        <th>
                            User ID
                        </th>

                        <th>
                            URL
                        </th>

                        <th>
                            Context
                        </th>

                        <th>
                            Created At
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach($logs as $log)

                    <tr>


                        <!-- ID -->

                        <td>
                            {{ $log->id }}
                        </td>


                        <!-- Level -->

                        <td>

                            <span
                                class="badge badge-{{ $log->level }}">
                                {{ $log->level }}
                            </span>

                        </td>


                        <!-- Channel -->

                        <td>
                            {{ $log->channel }}
                        </td>


                        <!-- Message -->

                        <td class="msg-cell">
                            {{ $log->message }}
                        </td>


                        <!-- Method -->

                        <td>
                            {{ $log->method ?? '-' }}
                        </td>


                        <!-- IP -->

                        <td>
                            {{ $log->ip ?? '-' }}
                        </td>


                        <!-- User ID -->

                        <td>
                            {{ $log->user_id ?? '-' }}
                        </td>


                        <!-- URL -->

                        <td class="url-cell">

                            {{ $log->url ?? '-' }}

                        </td>


                        <!-- Context -->

                        <td class="context-cell">

                            @if($log->context)

                            @php
                            $contextData = json_decode(
                            $log->context,
                            true
                            );
                            @endphp

                            @if(is_array($contextData))

                            <pre>{{ json_encode(
                                        $contextData,
                                        JSON_PRETTY_PRINT |
                                        JSON_UNESCAPED_SLASHES
                                    ) }}</pre>

                            @else

                            <pre>{{ $log->context }}</pre>

                            @endif

                            @else

                            -

                            @endif

                        </td>


                        <!-- Created At -->

                        <td class="created-at-cell">

                            {{ $log->created_at }}

                        </td>


                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        <!-- =====================================================
             PAGINATION
        ====================================================== -->

        <div class="pagination">

            {{ $logs->links() }}

        </div>


        @else

        <!-- =====================================================
             EMPTY STATE
        ====================================================== -->

        <div class="empty">

            🔍 No logs found matching your filters.

        </div>

        @endif


    </div>

</body>

</html>
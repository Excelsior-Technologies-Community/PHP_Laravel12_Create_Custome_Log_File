<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Log Management</title>

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
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
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
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .alert {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 1px 5px rgba(0,0,0,0.08);
            text-align: center;
        }

        .stat strong {
            display: block;
            font-size: 2rem;
            margin-bottom: 5px;
        }

        .stat span {
            color: #64748b;
            font-size: 0.8rem;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 1px 5px rgba(0,0,0,0.08);
        }

        .card h2 {
            font-size: 1.1rem;
            margin-bottom: 8px;
        }

        .card p {
            color: #64748b;
            font-size: 0.85rem;
            margin-bottom: 18px;
        }

        .btn {
            border: none;
            border-radius: 6px;
            padding: 10px 18px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
        }

        .btn-warning {
            background: #d97706;
            color: white;
        }

        .cleanup-form {
            display: flex;
            gap: 10px;
            align-items: end;
            flex-wrap: wrap;
        }

        .cleanup-form label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .cleanup-form input {
            padding: 10px;
            width: 180px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
        }

        .warning-box {
            background: #fff7ed;
            color: #9a3412;
            border: 1px solid #fed7aa;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 0.85rem;
        }

        @media(max-width: 700px) {

            .stats {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 0 10px;
            }

        }

    </style>

</head>

<body>

<div class="header">

    <h1>🧹 Log Management</h1>

    <div class="nav">

        <a href="{{ route('logs.dashboard') }}">
            📊 Dashboard
        </a>

        <a href="{{ route('logs.db') }}">
            🗄 DB Logs
        </a>

        <a href="{{ route('log.viewer') }}">
            📋 File Logs
        </a>

    </div>

</div>


<div class="container">

    @if(session('success'))

        <div class="alert alert-success">
            ✅ {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-error">
            ❌ {{ session('error') }}
        </div>

    @endif


    <div class="stats">

        <div class="stat">

            <strong>
                {{ $dbCount }}
            </strong>

            <span>
                Database Logs
            </span>

        </div>


        <div class="stat">

            <strong>
                {{ $fileExists ? 'Yes' : 'No' }}
            </strong>

            <span>
                custom.log Exists
            </span>

        </div>


        <div class="stat">

            <strong>
                {{ $dailyFiles }}
            </strong>

            <span>
                Daily Log Files
            </span>

        </div>

    </div>


    {{-- Clear DB --}}

    <div class="card">

        <h2>🗄 Clear Database Logs</h2>

        <p>
            Permanently delete all records from the logs database table.
        </p>

        <div class="warning-box">
            ⚠ This action cannot be undone.
        </div>

        <form
            method="POST"
            action="{{ route('logs.clear.database') }}"
            onsubmit="return confirm('Are you sure you want to delete ALL database logs?');"
        >

            @csrf

            <button
                type="submit"
                class="btn btn-danger"
            >
                🗑 Delete All Database Logs
            </button>

        </form>

    </div>


    {{-- Clear file --}}

    <div class="card">

        <h2>📄 Clear Custom Log File</h2>

        <p>
            Empty the main storage/logs/custom.log file without deleting it.
        </p>

        <form
            method="POST"
            action="{{ route('logs.clear.file') }}"
            onsubmit="return confirm('Are you sure you want to clear custom.log?');"
        >

            @csrf

            <button
                type="submit"
                class="btn btn-danger"
            >
                🧹 Clear custom.log
            </button>

        </form>

    </div>


    {{-- Old log cleanup --}}

    <div class="card">

        <h2>⏳ Clean Old Logs</h2>

        <p>
            Use the existing <strong>log:clean</strong> Artisan command
            to remove old database logs and daily log files.
        </p>

        <form
            method="POST"
            action="{{ route('logs.cleanup') }}"
            class="cleanup-form"
        >

            @csrf

            <div>

                <label>
                    Delete logs older than
                </label>

                <input
                    type="number"
                    name="days"
                    value="30"
                    min="1"
                    required
                >

            </div>

            <button
                type="submit"
                class="btn btn-warning"
                onclick="return confirm('Delete logs older than the selected number of days?');"
            >
                🧹 Run Cleanup
            </button>

        </form>

    </div>


    {{-- File information --}}

    <div class="card">

        <h2>📦 Log File Information</h2>

        <p>
            Main file:
            <strong>
                storage/logs/custom.log
            </strong>
        </p>

        <p>
            Current file size:
            <strong>
                {{ number_format($fileSize / 1024, 2) }} KB
            </strong>
        </p>

        <p>
            Daily log directory:
            <strong>
                storage/logs/custom/
            </strong>
        </p>

    </div>

</div>

</body>

</html>
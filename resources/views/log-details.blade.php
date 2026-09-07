<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Log Details</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;

            font-family: Arial, sans-serif;

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
        }

        .header a {
            background: #2563eb;

            color: white;

            padding: 8px 14px;

            text-decoration: none;

            border-radius: 6px;
        }

        .container {
            max-width: 1000px;

            margin: 30px auto;

            padding: 0 20px;
        }

        .card {
            background: white;

            padding: 25px;

            border-radius: 12px;

            box-shadow:
                0 1px 5px rgba(0, 0, 0, .08);
        }

        .row {
            display: grid;

            grid-template-columns:
                180px 1fr;

            gap: 15px;

            padding: 13px 0;

            border-bottom:
                1px solid #e5e7eb;
        }

        .label {
            font-weight: bold;

            color: #64748b;
        }

        .value {
            word-break: break-word;
        }

        pre {
            background: #f8fafc;

            padding: 15px;

            border-radius: 8px;

            white-space: pre-wrap;

            word-break: break-word;
        }

        @media(max-width:600px) {

            .row {
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

        <h2>
            🔎 Log Details
        </h2>

        <a href="{{ route('logs.db') }}">
            ← Back
        </a>

    </div>


    <div class="container">

        <div class="card">

            <div class="row">

                <div class="label">
                    ID
                </div>

                <div class="value">
                    {{ $log->id }}
                </div>

            </div>


            <div class="row">

                <div class="label">
                    Level
                </div>

                <div class="value">
                    {{ strtoupper($log->level) }}
                </div>

            </div>


            <div class="row">

                <div class="label">
                    Channel
                </div>

                <div class="value">
                    {{ $log->channel }}
                </div>

            </div>


            <div class="row">

                <div class="label">
                    Message
                </div>

                <div class="value">
                    {{ $log->message }}
                </div>

            </div>


            <div class="row">

                <div class="label">
                    HTTP Method
                </div>

                <div class="value">
                    {{ $log->method ?? '-' }}
                </div>

            </div>


            <div class="row">

                <div class="label">
                    IP Address
                </div>

                <div class="value">
                    {{ $log->ip ?? '-' }}
                </div>

            </div>


            <div class="row">

                <div class="label">
                    User ID
                </div>

                <div class="value">
                    {{ $log->user_id ?? '-' }}
                </div>

            </div>


            <div class="row">

                <div class="label">
                    URL
                </div>

                <div class="value">
                    {{ $log->url ?? '-' }}
                </div>

            </div>


            <div class="row">

                <div class="label">
                    Context
                </div>

                <div class="value">

                    @if($log->context)

                    @php

                    $context =
                    json_decode(
                    $log->context,
                    true
                    );

                    @endphp

                    @if(is_array($context))

                    <pre>{{ json_encode(
                            $context,
                            JSON_PRETTY_PRINT |
                            JSON_UNESCAPED_SLASHES
                        ) }}</pre>

                    @else

                    <pre>{{ $log->context }}</pre>

                    @endif

                    @else

                    -

                    @endif

                </div>

            </div>


            <div class="row">

                <div class="label">
                    Created At
                </div>

                <div class="value">
                    {{ $log->created_at }}
                </div>

            </div>


            <div class="row">

                <div class="label">
                    Updated At
                </div>

                <div class="value">
                    {{ $log->updated_at }}
                </div>

            </div>

        </div>

    </div>

</body>

</html>
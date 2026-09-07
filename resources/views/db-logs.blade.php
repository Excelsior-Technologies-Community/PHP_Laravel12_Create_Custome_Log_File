<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Custom Logs</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {

            min-height: 100vh;

            font-family:
                Inter,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;

            background:
                linear-gradient(135deg,
                    #eef2ff 0%,
                    #f8fafc 45%,
                    #ecfdf5 100%);

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 25px;

            color: #1e293b;

        }

        .page {

            width: 100%;

            max-width: 650px;

        }

        .card {

            background: rgba(255, 255, 255, .95);

            backdrop-filter: blur(18px);

            border:
                1px solid rgba(255, 255, 255, .8);

            border-radius: 24px;

            padding: 45px 40px;

            text-align: center;

            box-shadow:
                0 25px 60px rgba(15, 23, 42, .10);

        }

        .success-icon {

            width: 82px;

            height: 82px;

            margin: 0 auto 22px;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 38px;

            background:
                linear-gradient(135deg,
                    #10b981,
                    #059669);

            color: white;

            box-shadow:
                0 12px 25px rgba(16, 185, 129, .25);

        }

        .badge {

            display: inline-flex;

            align-items: center;

            gap: 6px;

            padding:
                7px 12px;

            border-radius: 30px;

            background: #ecfdf5;

            color: #059669;

            font-size: 11px;

            font-weight: 800;

            text-transform: uppercase;

            letter-spacing: .5px;

            margin-bottom: 15px;

        }

        .badge::before {

            content: "";

            width: 7px;

            height: 7px;

            border-radius: 50%;

            background: #10b981;

        }

        h1 {

            font-size: 28px;

            font-weight: 800;

            color: #0f172a;

            letter-spacing: -.7px;

            margin-bottom: 10px;

        }

        .description {

            color: #64748b;

            font-size: 14px;

            line-height: 1.7;

            margin-bottom: 28px;

        }

        .log-box {

            text-align: left;

            background: #f8fafc;

            border:
                1px solid #e2e8f0;

            border-radius: 15px;

            padding: 18px;

            margin-bottom: 25px;

        }

        .log-box-header {

            display: flex;

            align-items: center;

            gap: 9px;

            margin-bottom: 13px;

        }

        .log-box-icon {

            width: 32px;

            height: 32px;

            border-radius: 9px;

            background: #eef2ff;

            display: flex;

            align-items: center;

            justify-content: center;

        }

        .log-box-header strong {

            font-size: 13px;

            color: #334155;

        }

        .log-line {

            display: flex;

            align-items: center;

            gap: 10px;

            padding: 9px 0;

            border-bottom:
                1px solid #e5e7eb;

            font-size: 12px;

        }

        .log-line:last-child {

            border-bottom: none;

        }

        .log-dot {

            width: 8px;

            height: 8px;

            border-radius: 50%;

            background: #10b981;

            flex-shrink: 0;

        }

        .log-line span {

            color: #64748b;

        }

        .log-line strong {

            color: #334155;

            margin-left: auto;

            font-weight: 700;

        }

        .buttons {

            display: flex;

            gap: 10px;

            justify-content: center;

            flex-wrap: wrap;

        }

        .btn {

            min-width: 145px;

            height: 44px;

            padding:
                0 18px;

            border-radius: 11px;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 7px;

            text-decoration: none;

            font-size: 12px;

            font-weight: 750;

            transition: all .2s ease;

        }

        .btn-primary {

            color: white;

            background:
                linear-gradient(135deg,
                    #6366f1,
                    #4f46e5);

            box-shadow:
                0 8px 18px rgba(99, 102, 241, .20);

        }

        .btn-primary:hover {

            transform:
                translateY(-2px);

            box-shadow:
                0 12px 25px rgba(99, 102, 241, .28);

        }

        .btn-secondary {

            color: #475569;

            background: #f1f5f9;

            border:
                1px solid #e2e8f0;

        }

        .btn-secondary:hover {

            background: #e2e8f0;

            transform:
                translateY(-2px);

        }

        .footer {

            margin-top: 22px;

            font-size: 11px;

            color: #94a3b8;

        }

        @media (max-width: 600px) {

            .card {

                padding:
                    35px 22px;

            }

            h1 {

                font-size: 23px;

            }

            .buttons {

                flex-direction: column;

            }

            .btn {

                width: 100%;

            }

            .log-line {

                align-items: flex-start;

            }

            .log-line strong {

                text-align: right;

            }

        }
    </style>

</head>

<body>

    <div class="page">

        <div class="card">


            {{-- SUCCESS ICON --}}

            <div class="success-icon">
                ✓
            </div>


            {{-- STATUS --}}

            <div class="badge">
                Success
            </div>


            {{-- TITLE --}}

            <h1>
                Custom Logs Written Successfully!
            </h1>


            <p class="description">

                Your custom log entries have been successfully
                created and stored. You can now view and manage
                them from the log dashboard.

            </p>


            {{-- LOG INFORMATION --}}

            <div class="log-box">

                <div class="log-box-header">

                    <div class="log-box-icon">
                        📝
                    </div>

                    <strong>
                        Log Information
                    </strong>

                </div>


                <div class="log-line">

                    <div class="log-dot"></div>

                    <span>
                        Log Status
                    </span>

                    <strong>
                        Completed
                    </strong>

                </div>


                <div class="log-line">

                    <div class="log-dot"></div>

                    <span>
                        Log Channel
                    </span>

                    <strong>
                        Custom
                    </strong>

                </div>


                <div class="log-line">

                    <div class="log-dot"></div>

                    <span>
                        Log Levels
                    </span>

                    <strong>
                        Info · Warning · Error
                    </strong>

                </div>


            </div>


            {{-- BUTTONS --}}

            <div class="buttons">

                <a
                    href="{{ route('logs.db') }}"
                    class="btn btn-primary">

                    📊
                    View Database Logs

                </a>


                <a
                    href="{{ route('log.viewer') }}"
                    class="btn btn-secondary">

                    📋
                    View File Logs

                </a>

            </div>


            <div class="footer">

                Laravel Custom Logging System

            </div>


        </div>

    </div>

</body>

</html>
@php
    /**
     * Base admin email layout.
     *
     * Usage:
     *   @extends('admin.emails.layout')
     *   @section('content') ... @endsection
     *
     * Variables you can pass:
     * - $subject (string|null)
     * - $preheader (string|null)
     * - $brandName (string|null)   default: config('app.name')
     * - $brandColor (string|null)  default: #2563EB
     * - $footerNote (string|null)
     */
    $brandName  = $brandName ?? config('app.name', 'Admin');
    $brandColor = $brandColor ?? '#2563EB'; // blue-600
@endphp

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <meta name="format-detection" content="telephone=no,address=no,email=no,date=no,url=no">
    <title>{{ $subject ?? $brandName }}</title>

    <style>
        html, body { margin:0 !important; padding:0 !important; height:100% !important; width:100% !important; }
        * { -ms-text-size-adjust:100%; -webkit-text-size-adjust:100%; }
        table, td { mso-table-lspace:0pt !important; mso-table-rspace:0pt !important; border-collapse:collapse !important; }
        img { -ms-interpolation-mode:bicubic; border:0; outline:none; text-decoration:none; }
        a { text-decoration:none; }

        .preheader {
            display:none !important;
            visibility:hidden;
            mso-hide:all;
            font-size:1px;
            color:#f3f4f6;
            line-height:1px;
            max-height:0px;
            max-width:0px;
            opacity:0;
            overflow:hidden;
        }

        .bg { width:100%; background:#f3f4f6; padding:24px 12px; }
        .card { max-width:640px; margin:0 auto; background:#ffffff; border-radius:14px; overflow:hidden; border:1px solid #e5e7eb; }
        .header { padding:18px 22px; background: {{ $brandColor }}; color:#ffffff; }
        .brand { font-family: Arial, Helvetica, sans-serif; font-size:18px; font-weight:700; letter-spacing:0.2px; }
        .subtitle { font-family: Arial, Helvetica, sans-serif; font-size:13px; opacity:0.95; margin-top:4px; }
        .body { padding:22px; font-family: Arial, Helvetica, sans-serif; color:#111827; }
        .divider { height:1px; background:#eef2f7; margin:18px 0; }
        .muted { color:#6b7280; font-size:12px; line-height:1.6; }

        .btn {
            display:inline-block;
            padding:12px 16px;
            background: {{ $brandColor }};
            color:#ffffff !important;
            border-radius:10px;
            font-weight:700;
            font-family: Arial, Helvetica, sans-serif;
            font-size:14px;
        }

        .footer { padding:16px 22px; background:#fbfcfe; border-top:1px solid #eef2f7; }
    </style>
</head>
<body>
@if(!empty($preheader))
    <div class="preheader">{{ $preheader }}</div>
@endif

<table role="presentation" width="100%" class="bg">
    <tr>
        <td>
            <table role="presentation" width="100%" class="card">
                <tr>
                    <td class="header">
                        <div class="brand">{{ $brandName }}</div>
                        @if(!empty($subject))
                            <div class="subtitle">{{ $subject }}</div>
                        @endif
                    </td>
                </tr>

                <tr>
                    <td class="body">
                        @yield('content')

                        <div class="divider"></div>

                        <div class="muted">
                            This email was sent from {{ $brandName }}.
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="footer">
                        <div class="muted">
                            {{ $footerNote ?? 'If you did not expect this message, you can ignore it.' }}
                        </div>
                    </td>
                </tr>
            </table>

            <div class="muted" style="max-width:640px;margin:10px auto 0; text-align:center;">
                © {{ date('Y') }} {{ $brandName }}.
            </div>
        </td>
    </tr>
</table>
</body>
</html>
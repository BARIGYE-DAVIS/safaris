<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Verification</title>

    <link rel="stylesheet" href="{{ asset('build/assets/app-lVDNHE2B.css') }}">

    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <ink rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />    
    <style>
        /* Disable text selection site-wide */
        * {
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
            user-select: none !important;
        }

        /* Re-enable selection inside form fields only */
        input, textarea, select, [contenteditable="true"] {
            -webkit-user-select: text !important;
            -moz-user-select: text !important;
            -ms-user-select: text !important;
            user-select: text !important;
        }

        /* Protection overlay */
        #__cp_overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 999999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        #__cp_overlay.active {
            display: flex;
        }
        #__cp_box {
            background: #ffffff;
            border-radius: 16px;
            padding: 2.5rem 2rem;
            max-width: 420px;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4);
            font-family: system-ui, sans-serif;
            animation: __cp_pop 0.25s ease;
        }
        @keyframes __cp_pop {
            from { transform: scale(0.85); opacity: 0; }
            to   { transform: scale(1);    opacity: 1; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex justify-center items-center">

    <!-- Content Protection Overlay -->
    <div id="__cp_overlay" role="alertdialog" aria-modal="true" aria-labelledby="__cp_title">
        <div id="__cp_box">
            <div style="font-size:3rem; margin-bottom:0.75rem;"> <i class="fas fa-lock text-red-500"></i> </div>
            <h2 id="__cp_title" style="font-size:1.35rem; font-weight:700; color:#111; margin:0 0 0.5rem;">
                Content Protected
            </h2>
            <p style="font-size:0.95rem; color:#555; margin:0 0 1.25rem; line-height:1.6;">
                This page's content is protected.<br>
                Unauthorised copying or reproduction is strictly prohibited.
            </p>
            <p style="font-size:0.8rem; color:#aaa; margin:0 0 1.5rem;">
                &copy; {{ date('Y') }} Admin Panel. All rights reserved.
            </p>
            <button id="__cp_close"
                    style="background:#2563eb; color:#fff; border:none; border-radius:8px;
                           padding:0.65rem 2rem; font-size:0.95rem; font-weight:600; cursor:pointer;">
                OK, Got it
            </button>
        </div>
    </div>

    <div class="w-full max-w-sm mx-auto">
        <div class="bg-white shadow-md rounded px-8 py-6">
            <h2 class="text-2xl font-semibold mb-2 text-center text-gray-800">Verify Login</h2>
            <p class="text-sm text-gray-600 mb-6 text-center">
                A 6-character code was sent to your email. It expires in 3 minutes.
            </p>

            @if (session('status'))
                <div class="mb-4 p-3 rounded bg-green-50 border border-green-200 text-green-700 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-3 rounded bg-red-50 border border-red-200 text-red-700 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-3 rounded bg-red-50 border border-red-200 text-red-700 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.2fa.verify') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2" for="code">Verification code</label>
                    <input
                        type="text"
                        id="code"
                        name="code"
                        value="{{ old('code') }}"
                        placeholder="E.g. A1B2C3"
                        maxlength="6"
                        class="w-full px-3 py-2 border rounded border-gray-300 focus:outline-none focus:border-blue-500 tracking-widest uppercase"
                        required
                        autofocus
                    >
                    <p class="text-xs text-gray-500 mt-2">Enter the 6-character code (letters and numbers).</p>
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition duration-150">
                    Verify
                </button>
            </form>

            <div class="mt-6 text-center">
                <form method="POST" action="{{ route('admin.2fa.resend') }}">
                    @csrf
                    <button type="submit" class="text-sm text-blue-700 hover:text-blue-900 underline">
                        Resend code
                    </button>
                </form>

                <p class="text-xs text-gray-500 mt-4">
                    If you didn't request this login, you can close this page.
                </p>
            </div>
        </div>
    </div>

    <script>
        // ── Content Protection ──
        (function () {
            const overlay = document.getElementById('__cp_overlay');
            const closeBtn = document.getElementById('__cp_close');
            let _dismissTimer = null;

            function showAlert() {
                if (!overlay) return;
                overlay.classList.add('active');
                clearTimeout(_dismissTimer);
                _dismissTimer = setTimeout(hideAlert, 5000);
            }

            function hideAlert() {
                overlay?.classList.remove('active');
                clearTimeout(_dismissTimer);
            }

            closeBtn?.addEventListener('click', hideAlert);

            overlay?.addEventListener('click', function (e) {
                if (e.target === overlay) hideAlert();
            });

            // Block right-click
            document.addEventListener('contextmenu', function (e) {
                e.preventDefault();
                showAlert();
            });

            // Block text selection via mouse drag
            document.addEventListener('selectstart', function (e) {
                if (!isFormEl(e.target)) e.preventDefault();
            });

            // Block copy & cut
            document.addEventListener('copy', function (e) {
                if (!isFormEl(e.target)) { e.preventDefault(); showAlert(); }
            });
            document.addEventListener('cut', function (e) {
                if (!isFormEl(e.target)) { e.preventDefault(); showAlert(); }
            });

            // Block keyboard shortcuts
            document.addEventListener('keydown', function (e) {
                const key  = e.key.toLowerCase();
                const ctrl = e.ctrlKey || e.metaKey;

                if (ctrl && key === 'u') { e.preventDefault(); showAlert(); return; }
                if (ctrl && key === 's') { e.preventDefault(); showAlert(); return; }
                if (ctrl && key === 'p') { e.preventDefault(); showAlert(); return; }
                if (ctrl && key === 'a' && !isFormEl(e.target)) { e.preventDefault(); showAlert(); return; }
                if (ctrl && key === 'c' && !isFormEl(e.target)) { e.preventDefault(); showAlert(); return; }
                if (e.key === 'F12') { e.preventDefault(); showAlert(); return; }
                if (ctrl && e.shiftKey && key === 'i') { e.preventDefault(); showAlert(); return; }
                if (ctrl && e.shiftKey && key === 'j') { e.preventDefault(); showAlert(); return; }
                if (ctrl && e.shiftKey && key === 'c') { e.preventDefault(); showAlert(); return; }
            });

            function isFormEl(el) {
                if (!el) return false;
                const tag = (el.tagName || '').toLowerCase();
                return ['input', 'textarea', 'select'].includes(tag) || el.isContentEditable;
            }
        })();
    </script>
</body>
</html>
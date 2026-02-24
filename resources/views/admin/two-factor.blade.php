<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Verification</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="bg-gray-100 min-h-screen flex justify-center items-center">
    <div class="w-full max-w-sm mx-auto">
        <div class="bg-white shadow-md rounded px-8 py-6">
            <h2 class="text-2xl font-semibold mb-2 text-center text-gray-800">Verify Login</h2>
            <p class="text-sm text-gray-600 mb-6 text-center">
                 6-character code   was sent to your email. It expires in 3 minutes.
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
                    If you didn’t request this login, you can close this page.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
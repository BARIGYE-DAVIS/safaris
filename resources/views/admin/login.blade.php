<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body class="bg-gradient-to-br from-indigo-50 via-white to-blue-50 min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
            <div class="px-8 py-6 border-b bg-gray-50">
                <h2 class="text-2xl font-bold text-gray-800 text-center">Admin Login</h2>
                <p class="text-sm text-gray-600 text-center mt-1">Sign in to continue to the dashboard</p>
            </div>

            <div class="px-8 py-6">
                @if (session('error'))
                    <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('status'))
                    <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-gray-700 mb-2 text-sm font-medium" for="email">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full px-3 py-2.5 border rounded-lg border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="admin@example.com"
                            required
                            autofocus
                            autocomplete="username"
                        >
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2 text-sm font-medium" for="password">Password</label>

                        <div class="relative">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="w-full px-3 py-2.5 border rounded-lg border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 pr-24"
                                placeholder="Enter your password"
                                required
                                autocomplete="current-password"
                            >

                            <button
                                type="button"
                                id="togglePassword"
                                class="absolute inset-y-0 right-0 px-3 text-sm font-medium text-gray-600 hover:text-gray-900"
                                aria-label="Show password"
                            >
                                Show
                            </button>
                        </div>

                        <p class="text-xs text-gray-500 mt-2">
                            Your code will be sent to your email after login.
                        </p>
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-4 rounded-lg transition duration-150 shadow-sm"
                    >
                        Login
                    </button>
                </form>
            </div>

            <div class="px-8 py-5 bg-gray-50 border-t text-center">
                <p class="text-xs text-gray-500">
                    © {{ date('Y') }} Admin Panel
                </p>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const input = document.getElementById('password');
            const btn = document.getElementById('togglePassword');

            if (!input || !btn) return;

            btn.addEventListener('click', function () {
                const isPassword = input.getAttribute('type') === 'password';
                input.setAttribute('type', isPassword ? 'text' : 'password');
                btn.textContent = isPassword ? 'Hide' : 'Show';
                btn.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
            });
        })();
    </script>
</body>
</html>
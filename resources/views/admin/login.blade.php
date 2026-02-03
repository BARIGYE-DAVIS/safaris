<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js']) <!-- Use if you use Vite -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="bg-gray-100 min-h-screen flex justify-center items-center">
    <div class="w-full max-w-sm mx-auto">
        <div class="bg-white shadow-md rounded px-8 py-6">
            <h2 class="text-2xl font-semibold mb-6 text-center text-gray-800">Admin Login</h2>
            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2" for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="w-full px-3 py-2 border rounded border-gray-300 focus:outline-none focus:border-blue-500"
                           required autofocus>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2" for="password">Password</label>
                    <input type="password" id="password" name="password"
                           class="w-full px-3 py-2 border rounded border-gray-300 focus:outline-none focus:border-blue-500"
                           required>
                </div>
                @if($errors->any())
                    <div class="mb-4">
                        <span class="text-red-600 text-sm">{{ $errors->first() }}</span>
                    </div>
                @endif
                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition duration-150">
                    Login
                </button>
            </form>
        </div>
    </div>
</body>
</html>
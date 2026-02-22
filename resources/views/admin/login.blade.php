<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - TenCoffe</title>
    <link rel="icon" href="{{ asset('images/logo.jpeg') }}" type="image/jpeg">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-coffee-800 to-coffee-900 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8">
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo.jpeg') }}" alt="TenCoffe" class="w-20 h-20 mx-auto rounded-full border-4 border-coffee-200 shadow-lg mb-4">
            <h1 class="text-2xl font-extrabold text-coffee-800">TenCoffe Admin</h1>
            <p class="text-coffee-400 text-sm mt-1">Masuk ke panel admin</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 text-red-600 px-4 py-3 rounded-xl text-sm mb-6">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus class="input-field">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required class="input-field">
            </div>
            <button type="submit" class="btn-primary w-full py-3 rounded-xl text-lg">Masuk</button>
        </form>
    </div>
</body>
</html>

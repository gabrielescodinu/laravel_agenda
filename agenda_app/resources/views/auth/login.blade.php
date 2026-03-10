<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Agenda</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen flex items-center justify-center p-4">
<div class="w-full max-w-md bg-[#0d120d] border border-white/10 rounded-3xl p-8">
    <h1 class="text-2xl font-black mb-2">Accedi</h1>
    <p class="text-sm text-gray-400 mb-6">Entra per usare la tua agenda.</p>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-xs uppercase text-gray-500 mb-2">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                   class="w-full bg-[#141c15] border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-lime-300/40">
            @error('email')
            <p class="text-red-300 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-xs uppercase text-gray-500 mb-2">Password</label>
            <input id="password" name="password" type="password" required
                   class="w-full bg-[#141c15] border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-lime-300/40">
            @error('password')
            <p class="text-red-300 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <label class="flex items-center gap-2 text-sm text-gray-300">
            <input type="checkbox" name="remember" class="accent-lime-300">
            Ricordami
        </label>

        <button class="w-full bg-lime-300 text-black py-3 rounded-xl font-bold">Login</button>
    </form>

    <p class="text-sm text-gray-400 mt-6 text-center">
        Non hai un account?
        <a href="{{ route('register') }}" class="text-lime-300 hover:text-lime-200">Registrati</a>
    </p>
</div>
</body>
</html>

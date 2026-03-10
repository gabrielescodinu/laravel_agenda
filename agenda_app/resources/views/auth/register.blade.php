<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registrazione - Agenda</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen flex items-center justify-center p-4">
<div class="w-full max-w-md bg-[#0d120d] border border-white/10 rounded-3xl p-8">
    <h1 class="text-2xl font-black mb-2">Crea account</h1>
    <p class="text-sm text-gray-400 mb-6">Registrati per accedere alla dashboard.</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-xs uppercase text-gray-500 mb-2">Nome</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                   class="w-full bg-[#141c15] border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-lime-300/40">
            @error('name')
            <p class="text-red-300 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-xs uppercase text-gray-500 mb-2">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required
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

        <div>
            <label for="password_confirmation" class="block text-xs uppercase text-gray-500 mb-2">Conferma password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required
                   class="w-full bg-[#141c15] border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-lime-300/40">
        </div>

        <button class="w-full bg-lime-300 text-black py-3 rounded-xl font-bold">Registrati</button>
    </form>

    <p class="text-sm text-gray-400 mt-6 text-center">
        Hai già un account?
        <a href="{{ route('login') }}" class="text-lime-300 hover:text-lime-200">Accedi</a>
    </p>
</div>
</body>
</html>

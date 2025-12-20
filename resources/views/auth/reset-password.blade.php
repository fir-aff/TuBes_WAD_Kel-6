<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Kantin Kampus</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-orange-500 via-red-500 to-pink-500 min-h-screen flex items-center justify-center p-4">
    <!-- Background decoration -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-10 w-72 h-72 bg-white opacity-10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-10 w-72 h-72 bg-white opacity-10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-md">
        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 space-y-6">
            <!-- Logo -->
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-orange-100 to-red-100 rounded-full mb-4">
                    <span class="text-3xl">🔐</span>
                </div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                    Buat Password Baru
                </h1>
                <p class="text-slate-500 mt-1 text-sm">Masukkan password baru Anda</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email (hidden) -->
                <input type="hidden" name="email" value="{{ $request->email }}">

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                        🔒 Password Baru
                    </label>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition bg-slate-50"
                           placeholder="Masukkan password minimal 8 karakter"
                           required 
                           autofocus>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">
                        ✓ Konfirmasi Password
                    </label>
                    <input type="password" 
                           name="password_confirmation" 
                           id="password_confirmation" 
                           class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition bg-slate-50"
                           placeholder="Konfirmasi password Anda"
                           required>
                    @error('password_confirmation')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold rounded-lg hover:shadow-lg transition duration-300 mt-6">
                    🔐 Reset Password
                </button>
            </form>

            <!-- Back to Login -->
            <div class="text-center">
                <a href="{{ route('login') }}" class="text-sm font-semibold text-orange-600 hover:text-orange-700 transition">
                    ← Kembali ke login
                </a>
            </div>
        </div>

        <!-- Footer info -->
        <p class="text-center text-white text-sm mt-8 opacity-90">
            © 2025 Kantin Kampus - Pesan makanan dengan mudah 🍽️
        </p>
    </div>
</body>
</html>

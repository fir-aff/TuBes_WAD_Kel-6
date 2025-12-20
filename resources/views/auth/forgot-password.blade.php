<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Kantin Kampus</title>
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
                    <span class="text-3xl">🔑</span>
                </div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                    Reset Password
                </h1>
                <p class="text-slate-500 mt-1 text-sm">Masukkan email untuk menerima link reset</p>
            </div>

            <!-- Info Message -->
            @if (session('status'))
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-lg p-4">
                    <p class="text-green-700 font-medium text-sm">✓ {{ session('status') }}</p>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                        📧 Email
                    </label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition bg-slate-50"
                           placeholder="Masukkan email akun Anda"
                           value="{{ old('email') }}"
                           required 
                           autofocus>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold rounded-lg hover:shadow-lg transition duration-300 mt-6">
                    📧 Kirim Link Reset
                </button>
            </form>

            <!-- Divider -->
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-slate-500">atau</span>
                </div>
            </div>

            <!-- Back to Login -->
            <div class="text-center">
                <p class="text-slate-600">
                    Ingat password?
                    <a href="{{ route('login') }}" class="font-bold text-orange-600 hover:text-orange-700 transition">
                        Kembali ke login
                    </a>
                </p>
            </div>
        </div>

        <!-- Footer info -->
        <p class="text-center text-white text-sm mt-8 opacity-90">
            © 2025 Kantin Kampus - Pesan makanan dengan mudah 🍽️
        </p>
    </div>
</body>
</html>

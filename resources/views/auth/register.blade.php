<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Kantin Kampus</title>
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
                    <span class="text-3xl">🍴</span>
                </div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                    Kantin Kampus
                </h1>
                <p class="text-slate-500 mt-1">Buat akun untuk mulai memesan</p>
            </div>

            <!-- Success Alert -->
            @if(session('success'))
                <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-lg text-green-700 font-medium text-sm">
                    ✓ {{ session('success') }}
                </div>
            @endif

            <!-- Error Alert -->
            @if ($errors->any())
                <div class="p-4 bg-gradient-to-r from-red-50 to-pink-50 border-2 border-red-200 rounded-lg">
                    <p class="text-red-700 font-bold text-sm mb-2">⚠️ Ada kesalahan:</p>
                    <ul class="list-disc list-inside space-y-1 text-red-600 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">
                        👤 Nama Lengkap
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition bg-slate-50"
                           placeholder="Nama lengkap Anda"
                           value="{{ old('name') }}"
                           required 
                           autofocus>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                        📧 Email
                    </label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition bg-slate-50"
                           placeholder="contoh@email.com"
                           value="{{ old('email') }}"
                           required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                        🔒 Kata Sandi
                    </label>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition bg-slate-50"
                           placeholder="Minimal 8 karakter"
                           required>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">
                        🔐 Konfirmasi Kata Sandi
                    </label>
                    <input type="password" 
                           name="password_confirmation" 
                           id="password_confirmation" 
                           class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition bg-slate-50"
                           placeholder="Ulangi kata sandi"
                           required>
                    @error('password_confirmation')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Register Button -->
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold rounded-lg hover:shadow-lg transition duration-300 mt-6">
                    ✓ Daftar Sekarang
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

            <!-- Login Link -->
            <div class="text-center">
                <p class="text-slate-600">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-bold text-orange-600 hover:text-orange-700 transition">
                        Login di sini
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

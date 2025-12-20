<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - Kantin Kampus</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-b from-slate-50 to-slate-100 min-h-screen">
    <div class="max-w-2xl mx-auto px-4 py-8 sm:py-12">
        <!-- Back Button -->
        <a href="{{ route('welcome') }}" class="inline-flex items-center gap-2 text-orange-600 font-semibold hover:text-orange-700 mb-6 transition">
            ← Kembali ke Beranda
        </a>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-orange-500 to-red-500 p-8 text-white">
                <h1 class="text-3xl font-bold">👤 Edit Profil</h1>
                <p class="text-orange-50 mt-1">Kelola informasi akun Anda</p>
            </div>

            <div class="p-8 space-y-8">
                <!-- Success Alert -->
                @if(session('success'))
                    <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-lg text-green-700 font-medium">
                        ✓ {{ session('success') }}
                    </div>
                @endif

                <!-- Section 1: Edit Profil -->
                <div class="border-b pb-8">
                    <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                        📝 Informasi Dasar
                    </h2>
                    
                    <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap</label>
                            <input type="text" id="name" name="name" 
                                   class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition bg-slate-50"
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                            <input type="email" id="email" name="email" 
                                   class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition bg-slate-50"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" class="w-full py-3 bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold rounded-lg hover:shadow-lg transition">
                            💾 Simpan Perubahan
                        </button>
                    </form>
                </div>

                <!-- Section 2: Ubah Password -->
                <div class="border-b pb-8">
                    <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                        🔒 Ubah Password
                    </h2>
                    
                    <form action="{{ route('profile.password') }}" method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label for="current_password" class="block text-sm font-semibold text-slate-700 mb-2">Password Saat Ini</label>
                            <input type="password" id="current_password" name="current_password" 
                                   class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition bg-slate-50"
                                   required>
                            @error('current_password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Password Baru</label>
                            <input type="password" id="password" name="password" 
                                   class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition bg-slate-50"
                                   required>
                            @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Password Baru</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" 
                                   class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition bg-slate-50"
                                   required>
                        </div>

                        <button type="submit" class="w-full py-3 bg-gradient-to-r from-blue-500 to-cyan-500 text-white font-bold rounded-lg hover:shadow-lg transition">
                            🔐 Ubah Password
                        </button>
                    </form>
                </div>

                <!-- Section 3: Quick Links -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <a href="{{ route('order.history') }}" class="p-4 bg-gradient-to-br from-purple-50 to-purple-100 border-2 border-purple-200 rounded-lg hover:shadow-md transition text-center font-semibold text-purple-700">
                        📜 Riwayat Pembelian
                    </a>
                    <a href="{{ route('welcome') }}" class="p-4 bg-gradient-to-br from-green-50 to-green-100 border-2 border-green-200 rounded-lg hover:shadow-md transition text-center font-semibold text-green-700">
                        🏠 Kembali ke Menu
                    </a>
                </div>
            </div>
        </div>
    </div>

    @vite('resources/js/app.js')
</body>
</html>
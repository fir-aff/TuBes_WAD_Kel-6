<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kantin Kampus - Pesan Makanan Favorit Anda</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-b from-slate-50 to-slate-100 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <span class="text-2xl">🍴</span>
                    <span class="text-xl font-bold bg-gradient-to-r from-orange-500 to-red-500 bg-clip-text text-transparent">Kantin Kampus</span>
                </div>

                <!-- Search (hidden on mobile) -->
                <div class="hidden md:flex flex-1 mx-8">
                    <form method="GET" action="{{ route('welcome') }}" class="w-full">
                        <div class="relative">
                            <input type="search" name="q" value="{{ $q ?? '' }}" placeholder="Cari makanan atau minuman..." class="w-full px-4 py-2 rounded-lg bg-slate-100 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-orange-500 transition">
                            <button type="submit" class="absolute right-1 top-1/2 -translate-y-1/2 px-3 py-1 rounded-md bg-orange-500 text-white hover:bg-orange-600 transition">Cari</button>
                        </div>
                    </form>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center space-x-4">
                    <!-- Cart -->
                    <a href="{{ route('cart.show') }}" class="relative inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-r from-orange-50 to-red-50 hover:shadow-md transition">
                        <span class="text-xl">🛒</span>
                        @php
                            $cartCount = count(session('cart', []));
                        @endphp
                        @if($cartCount > 0)
                            <span class="absolute -top-2 -right-2 w-5 h-5 bg-gradient-to-r from-orange-500 to-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    <!-- History -->
                    <a href="{{ route('order.history') }}" class="hidden sm:inline-flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50 hover:shadow-md transition text-xl">
                        📜
                    </a>

                    <!-- Auth -->
                    @guest
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg bg-gradient-to-r from-orange-500 to-red-500 text-white font-medium hover:shadow-lg transition">
                            Login
                        </a>
                    @endguest
                    @auth
                        <div class="relative group">
                            <button class="px-4 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 font-medium transition flex items-center gap-2">
                                👤 {{ auth()->user()->name }}
                                <span class="text-xs">▼</span>
                            </button>
                            <div class="absolute right-0 mt-0 w-48 bg-white rounded-lg shadow-xl opacity-0 group-hover:opacity-100 invisible group-hover:visible transition">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-slate-50 transition border-b">Profil Saya</a>
                                <form action="{{ route('logout') }}" method="POST" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-red-50 text-red-600 transition" onclick="return confirm('Yakin ingin logout?')">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        @if(session('success'))
            <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg text-green-700 font-medium">
                ✓ {{ session('success') }}
            </div>
        @endif

        <div class="bg-gradient-to-r from-orange-500 via-red-500 to-pink-500 rounded-2xl p-8 sm:p-12 text-white shadow-xl mb-8">
            <h1 class="text-3xl sm:text-4xl font-bold mb-2">Selamat Datang di Kantin Kampus! 🎉</h1>
            <p class="text-lg text-orange-50 mb-6">Pesan makanan favorit dengan mudah, cepat, dan terjangkau. Nikmati berbagai pilihan menu dari makanan berat hingga dessert lezat.</p>
            <a href="#menu" class="inline-block px-6 py-3 bg-white text-orange-600 font-bold rounded-lg hover:shadow-lg transition">
                Lihat Menu Hari Ini
            </a>
        </div>

        <!-- Category Filter -->
        <div class="mb-8">
            <h2 class="text-lg font-bold text-slate-800 mb-4">Pilih Kategori</h2>
            <div class="flex flex-wrap gap-3">
                @foreach($kategoriList as $kategori)
                    <a href="{{ route('welcome', ['kategori' => $kategori]) }}" 
                       class="px-6 py-2 rounded-full font-medium transition {{ ($kategoriPilihan ?? 'Semua') == $kategori ? 'bg-gradient-to-r from-orange-500 to-red-500 text-white shadow-lg' : 'bg-white text-slate-700 border-2 border-slate-200 hover:border-orange-500' }}">
                        {{ $kategori }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Menu Title -->
        <h2 class="text-2xl font-bold text-slate-800 mb-6" id="menu">
            @if($kategoriPilihan && $kategoriPilihan !== 'Semua')
                📌 {{ $kategoriPilihan }}
            @else
                🍽️ Semua Menu Tersedia
            @endif
        </h2>

        <!-- Menu Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse ($menus as $menu)
                @php
                    $kategori_menu = strtolower($menu->kategori);
                    $badge = match($kategori_menu) {
                        'minuman' => ['bg-blue-100', 'text-blue-700', '🥤'],
                        'makanan berat' => ['bg-red-100', 'text-red-700', '🍖'],
                        'camilan' => ['bg-yellow-100', 'text-yellow-700', '🍟'],
                        'dessert' => ['bg-pink-100', 'text-pink-700', '🍰'],
                        default => ['bg-slate-100', 'text-slate-700', '🍽️'],
                    };
                @endphp
                <div class="group bg-white rounded-xl shadow-md hover:shadow-2xl transition duration-300 overflow-hidden">
                    <!-- Image -->
                    <div class="relative h-48 bg-gradient-to-br from-slate-100 to-slate-200 overflow-hidden">
                        @if ($menu->gambar)
                            <img src="{{ asset('storage/menu/' . $menu->gambar) }}" alt="{{ $menu->nama }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-4xl">
                                {{ $badge[2] }}
                            </div>
                        @endif
                        <!-- Category Badge -->
                        <div class="absolute top-3 left-3 {{ $badge[0] }} {{ $badge[1] }} px-3 py-1 rounded-full text-sm font-bold">
                            {{ ucfirst($menu->kategori) }}
                        </div>
                        <!-- Quick Add Button (visible) - AJAX enabled -->
                        <form data-quick-add="{{ $menu->id }}" class="absolute top-3 right-3">
                            @csrf
                            <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                            <input type="hidden" name="nama" value="{{ $menu->nama }}">
                            <input type="hidden" name="harga" value="{{ $menu->harga }}">
                            <input type="hidden" name="gambar" value="{{ $menu->gambar }}">
                            <button type="submit" class="px-3 py-1 rounded-md bg-white/95 text-orange-600 font-semibold shadow hover:scale-105 transition">Tambah</button>
                        </form>
                    </div>

                    <!-- Content -->
                    <div class="p-4 flex flex-col h-full">
                        <h3 class="font-bold text-lg text-slate-800 mb-2 line-clamp-2">{{ $menu->nama }}</h3>
                        <p class="text-sm text-slate-600 mb-3 line-clamp-2">{{ $menu->deskripsi ?? 'Menu pilihan terbaik kami' }}</p>

                        <div class="mt-auto">
                            <div class="text-2xl font-bold text-transparent bg-gradient-to-r from-orange-500 to-red-500 bg-clip-text mb-4">
                                Rp {{ number_format($menu->harga, 0, ',', '.') }}
                            </div>

                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                <input type="hidden" name="nama" value="{{ $menu->nama }}">
                                <input type="hidden" name="harga" value="{{ $menu->harga }}">
                                <input type="hidden" name="gambar" value="{{ $menu->gambar }}">
                                <button type="submit" class="w-full py-2 bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold rounded-lg hover:shadow-lg transition">
                                    + Tambah ke Keranjang
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-8 text-center">
                        <p class="text-lg text-blue-700 font-medium">😔 Menu tidak tersedia untuk kategori ini</p>
                        <a href="{{ route('welcome') }}" class="inline-block mt-4 px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                            Lihat Semua Menu
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Toast container for dynamic feedback -->
    <div id="toastContainer" class="fixed bottom-6 right-6 z-50 space-y-2"></div>

    <!-- AJAX Quick Add Script -->
    <script>
        // Function to show toast
        function showToast(message, isSuccess = true) {
            const container = document.getElementById('toastContainer');
            const toastId = 'toast-' + Date.now();
            
            const toast = document.createElement('div');
            toast.id = toastId;
            toast.className = 'max-w-sm w-full bg-white border border-slate-100 shadow-lg rounded-xl p-4 flex items-start gap-3 animate-fade-in';
            toast.innerHTML = `
                <div class="text-2xl">${isSuccess ? '✅' : '❌'}</div>
                <div class="flex-1">
                    <p class="font-semibold text-slate-800">${message}</p>
                </div>
                <button onclick="document.getElementById('${toastId}').remove()" class="text-slate-500 hover:text-slate-700">&times;</button>
            `;
            
            container.appendChild(toast);
            
            // Auto-hide after 3 seconds
            setTimeout(() => toast.remove(), 3000);
        }

        // Function to update cart count in navbar
        function updateCartCount() {
            fetch('{{ route("cart.show") }}')
                .then(response => response.text())
                .then(html => {
                    // Extract cart count from the response
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const cartIcon = document.querySelector('a[href="{{ route("cart.show") }}"]');
                    const newCartIcon = doc.querySelector('a[href="{{ route("cart.show") }}"]');
                    if (cartIcon && newCartIcon) {
                        cartIcon.innerHTML = newCartIcon.innerHTML;
                    }
                });
        }

        // AJAX form submission for quick add
        document.querySelectorAll('form[data-quick-add]').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                fetch('{{ route("cart.add") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json().catch(() => ({ success: true })))
                .then(data => {
                    showToast('Menu berhasil ditambahkan ke keranjang!', true);
                    updateCartCount();
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Gagal menambahkan ke keranjang. Silakan coba lagi.', false);
                });
            });
        });
    </script>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300 mt-16 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="mb-2">© 2025 Kantin Kampus. Semua hak dilindungi.</p>
            <p class="text-sm">Pesan dengan mudah, nikmati dengan senang. 🍴</p>
        </div>
    </footer>

    @vite('resources/js/app.js')
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Kantin Kampus</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-b from-slate-50 to-slate-100 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-2">
                    <span class="text-2xl">🛒</span>
                    <span class="text-xl font-bold text-orange-600">Keranjang Belanja</span>
                </div>
                <a href="{{ route('welcome') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg font-semibold text-slate-700 transition">
                    ← Kembali ke Menu
                </a>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-lg text-green-700 font-medium">
                ✓ {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="mb-6 p-4 bg-gradient-to-r from-red-50 to-pink-50 border-2 border-red-200 rounded-lg text-red-700 font-medium">
                ✗ {{ session('error') }}
            </div>
        @endif

        @forelse ($cartItems as $id => $item)
            <!-- Cart Item Card -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition mb-4 overflow-hidden">
                <div class="p-6 flex flex-col sm:flex-row gap-6 items-start sm:items-center">
                    <!-- Image -->
                    @if(isset($item['gambar']))
                        <img src="{{ asset('storage/menu/' . $item['gambar']) }}" alt="{{ $item['nama'] }}" 
                             class="w-24 h-24 rounded-lg object-cover flex-shrink-0">
                    @else
                        <div class="w-24 h-24 rounded-lg bg-gradient-to-br from-orange-100 to-red-100 flex items-center justify-center text-3xl flex-shrink-0">
                            🍽️
                        </div>
                    @endif

                    <!-- Details -->
                    <div class="flex-grow">
                        <h3 class="text-xl font-bold text-slate-800">{{ $item['nama'] }}</h3>
                        <p class="text-lg text-orange-600 font-semibold mt-2">
                            Rp {{ number_format($item['harga'], 0, ',', '.') }}
                        </p>
                    </div>

                    <!-- Quantity & Subtotal -->
                    <div class="flex flex-col sm:flex-row gap-4 items-end sm:items-center w-full sm:w-auto">
                        <form action="{{ route('cart.update') }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            <input type="hidden" name="menu_id" value="{{ $id }}">
                            <label class="font-semibold text-slate-600 text-sm">Qty:</label>
                            <input type="number" name="kuantitas" value="{{ $item['kuantitas'] }}" 
                                   class="w-16 px-3 py-2 border-2 border-slate-200 rounded-lg focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition text-center"
                                   onchange="this.form.submit()" min="1">
                        </form>

                        <div class="text-xl font-bold text-orange-600 w-full sm:w-32 text-right">
                            Rp {{ number_format($item['harga'] * $item['kuantitas'], 0, ',', '.') }}
                        </div>

                        <form action="{{ route('cart.remove') }}" method="POST">
                            @csrf
                            <input type="hidden" name="menu_id" value="{{ $id }}">
                            <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-bold transition">
                                🗑️ Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <!-- Empty Cart -->
            <div class="bg-white rounded-xl shadow-md p-12 text-center">
                <div class="text-6xl mb-4">🛒</div>
                <h2 class="text-2xl font-bold text-slate-800 mb-2">Keranjang Kosong</h2>
                <p class="text-slate-600 mb-6">Mulai pilih menu favorit Anda sekarang!</p>
                <a href="{{ route('welcome') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold rounded-lg hover:shadow-lg transition">
                    Lihat Menu
                </a>
            </div>
        @endforelse

        <!-- Summary Card -->
        @if(count($cartItems) > 0)
            <div class="mt-8 bg-gradient-to-r from-orange-50 to-red-50 rounded-xl border-2 border-orange-200 p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-slate-800">Ringkasan Pesanan</h2>
                    <span class="text-4xl font-bold text-transparent bg-gradient-to-r from-orange-500 to-red-500 bg-clip-text">
                        Rp {{ number_format($totalPembayaran, 0, ',', '.') }}
                    </span>
                </div>

                <!-- Checkout Button -->
                <button type="button" id="checkoutBtn"
                        class="w-full py-4 bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold text-lg rounded-lg hover:shadow-lg hover:scale-105 transition">
                    💳 Lanjutkan Pembayaran
                </button>
            </div>

            <!-- Payment Modal (Tailwind) -->
            <div id="paymentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden animate-fade-in">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-orange-500 to-red-500 text-white p-6">
                        <h2 class="text-2xl font-bold">💳 Konfirmasi Pembayaran</h2>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-8 text-center space-y-6">
                        <div>
                            <p class="text-slate-600 text-sm mb-4">Silakan scan QR code di bawah untuk membayar:</p>
                            <div class="bg-gradient-to-br from-slate-100 to-slate-200 rounded-lg p-4 inline-block">
                                @if(file_exists(public_path('storage/qrcode.jpg')))
                                    <img src="{{ asset('storage/qrcode.jpg') }}" alt="QR Code" class="w-48 h-48 rounded-lg">
                                @else
                                    <div class="w-48 h-48 flex items-center justify-center text-slate-400 font-semibold">
                                        QR Code (belum tersedia)
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="bg-orange-50 rounded-lg p-4 border-2 border-orange-200">
                            <p class="text-slate-600 text-sm mb-1">Total Pembayaran</p>
                            <p class="text-3xl font-bold text-transparent bg-gradient-to-r from-orange-500 to-red-500 bg-clip-text">
                                Rp {{ number_format($totalPembayaran, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="bg-blue-50 rounded-lg p-3 border-2 border-blue-200">
                            <p class="text-blue-700 text-sm font-medium">ℹ️ Setelah melakukan pembayaran, klik tombol "Selesai Bayar" di bawah</p>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="border-t p-4 flex gap-3 justify-end">
                        <button type="button" id="cancelPaymentBtn"
                                class="px-6 py-2 bg-slate-200 text-slate-800 rounded-lg font-semibold hover:bg-slate-300 transition">
                            Batal
                        </button>
                        <form action="{{ route('order.done') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-lg font-semibold hover:shadow-lg transition">
                                ✓ Selesai Bayar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Overlay Close -->
            <script>
                const checkoutBtn = document.getElementById('checkoutBtn');
                const paymentModal = document.getElementById('paymentModal');
                const cancelPaymentBtn = document.getElementById('cancelPaymentBtn');

                checkoutBtn.addEventListener('click', () => {
                    paymentModal.classList.remove('hidden');
                });

                cancelPaymentBtn.addEventListener('click', () => {
                    paymentModal.classList.add('hidden');
                });

                paymentModal.addEventListener('click', (e) => {
                    if (e.target === paymentModal) {
                        paymentModal.classList.add('hidden');
                    }
                });
            </script>
        @endif
    </div>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>

    @vite('resources/js/app.js')
</body>
</html>

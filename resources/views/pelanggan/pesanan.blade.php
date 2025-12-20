<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - Kantin Kampus</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-b from-slate-50 to-slate-100 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-2">
                    <span class="text-2xl">📜</span>
                    <span class="text-xl font-bold text-orange-600">Riwayat Pesanan</span>
                </div>
                <a href="{{ route('welcome') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg font-semibold text-slate-700 transition">
                    ← Kembali ke Menu
                </a>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-lg text-green-700 font-medium">
                ✓ {{ session('success') }}
            </div>
        @endif

        @forelse($orders as $order)
            <!-- Order Card -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition mb-4 overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row gap-6 items-start sm:items-center">
                        <!-- Product Image -->
                        <img src="{{ asset('storage/' . ($order->menu->gambar ?? 'default_product.png')) }}"
                             alt="{{ $order->menu->nama }}" 
                             class="w-24 h-24 rounded-lg object-cover flex-shrink-0">

                        <!-- Order Details -->
                        <div class="flex-grow">
                            <h3 class="text-xl font-bold text-slate-800">{{ $order->menu->nama }}</h3>
                            <p class="text-slate-600 mt-1">Jumlah: <span class="font-semibold">{{ $order->jumlah }} item</span></p>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="text-sm font-medium">Status:</span>
                                <span class="px-4 py-1 rounded-full text-white font-semibold text-sm
                                    @if($order->status == 'Selesai') bg-gradient-to-r from-green-500 to-emerald-500
                                    @elseif ($order->status == 'Dibatalkan') bg-gradient-to-r from-red-500 to-pink-500
                                    @else bg-gradient-to-r from-yellow-500 to-orange-500 @endif">
                                    {{ $order->status }}
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        @if($order->status == 'Selesai')
                            <div class="flex flex-col gap-2 w-full sm:w-auto">
                                @php
                                    $hasReviewed = $order->reviews->where('user_id', Auth::id())->isNotEmpty();
                                @endphp

                                @if($hasReviewed)
                                    <a href="{{ route('reviews.show_product', $order->menu->id) }}" 
                                       class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-semibold transition text-center">
                                        👁️ Lihat Ulasan
                                    </a>
                                @else
                                    <button type="button" 
                                            class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 hover:shadow-lg text-white rounded-lg font-semibold transition"
                                            data-bs-toggle="modal" data-bs-target="#reviewModal{{ $order->id }}">
                                        ⭐ Beri Ulasan
                                    </button>
                                @endif

                                <form action="{{ route('orders.reorder', $order->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-orange-500 to-red-500 hover:shadow-lg text-white rounded-lg font-semibold transition">
                                        🔄 Beli Lagi
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Review Modal -->
            <div class="modal fade" id="reviewModal{{ $order->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content rounded-2xl">
                        <div class="modal-header bg-gradient-to-r from-yellow-500 to-orange-500 text-white border-0">
                            <h5 class="modal-title font-bold">⭐ Ulasan untuk {{ $order->menu->nama }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body p-8 space-y-6">
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                                <input type="hidden" name="menu_id" value="{{ $order->menu->id }}">

                                <!-- Star Rating -->
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-3">Berikan Rating:</label>
                                    <div class="star-rating flex gap-2 text-4xl" data-order-id="{{ $order->id }}">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <span class="star cursor-pointer hover:scale-125 transition opacity-50" data-value="{{ $i }}">⭐</span>
                                        @endfor
                                        <input type="hidden" name="rating" class="rating-input" value="0" required>
                                    </div>
                                </div>

                                <!-- Comment -->
                                <div>
                                    <label for="comment{{ $order->id }}" class="block text-sm font-semibold text-slate-700 mb-2">Ulasan Anda:</label>
                                    <textarea class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition bg-slate-50" 
                                              name="comment" 
                                              id="comment{{ $order->id }}" 
                                              rows="4" 
                                              placeholder="Bagikan pengalaman Anda dengan produk ini..." 
                                              required></textarea>
                                </div>

                                <!-- Image Upload -->
                                <div>
                                    <label for="image{{ $order->id }}" class="block text-sm font-semibold text-slate-700 mb-2">Foto Pesanan (Opsional):</label>
                                    <input type="file" 
                                           class="block w-full px-4 py-3 border-2 border-dashed border-slate-200 rounded-lg focus:outline-none focus:border-orange-500 transition bg-slate-50" 
                                           name="image" 
                                           id="image{{ $order->id }}" 
                                           accept="image/*">
                                </div>
                            </div>

                            <div class="modal-footer border-t p-4 gap-3">
                                <button type="button" class="px-6 py-2 bg-slate-200 text-slate-800 rounded-lg font-semibold hover:bg-slate-300 transition" data-bs-dismiss="modal">
                                    Batal
                                </button>
                                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-lg font-semibold hover:shadow-lg transition">
                                    ✓ Kirim Ulasan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-md p-12 text-center">
                <div class="text-6xl mb-4">📜</div>
                <h2 class="text-2xl font-bold text-slate-800 mb-2">Belum Ada Riwayat Pesanan</h2>
                <p class="text-slate-600 mb-6">Mulai pesan makanan favorit Anda sekarang!</p>
                <a href="{{ route('welcome') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold rounded-lg hover:shadow-lg transition">
                    Lihat Menu
                </a>
            </div>
        @endforelse
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Handle star rating
            document.querySelectorAll('.star-rating').forEach(ratingDiv => {
                const orderId = ratingDiv.dataset.orderId;
                const ratingInput = ratingDiv.querySelector('.rating-input');
                const stars = ratingDiv.querySelectorAll('.star');

                stars.forEach(star => {
                    star.addEventListener('click', function() {
                        const value = parseInt(this.dataset.value);
                        ratingInput.value = value;
                        
                        stars.forEach(s => {
                            if (parseInt(s.dataset.value) <= value) {
                                s.classList.remove('opacity-50');
                                s.classList.add('opacity-100');
                            } else {
                                s.classList.remove('opacity-100');
                                s.classList.add('opacity-50');
                            }
                        });
                    });
                });
            });
        });
    </script>
    @vite('resources/js/app.js')
</body>
</html>
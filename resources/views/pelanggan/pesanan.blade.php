<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pesanan - Kantin Kampus</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #7f00ff, #7c43bd); 
            min-height: 100vh; 
            font-family: 'Segoe UI', sans-serif; 
        }
        .container {
            max-width: 800px; 
        }
        .navbar {
            background-color:rgba(0, 0, 0, 0.25); 
        }
        .card-pesanan {
            background: white; 
            border-radius: 1rem; 
            padding: 1.5rem; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.1); 
        }
        .badge-status {
            font-size: 0.85rem; 
        }
        .topbar {
            background-color: rgba(255, 255, 255, 0.1); 
            backdrop-filter: blur(10px); 
        }
        .product-image {
            width: 80px; /* Ukuran gambar produk */
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark topbar"> 
    <div class="container"> 
        <span class="navbar-brand fw-bold text-white">Riwayat Pesanan</span> 
        <a href="{{ route('welcome') }}" class="btn btn-light btn-sm">Kembali ke Beranda</a> 
    </div>
</nav>

<main class="container my-5">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @forelse($orders as $order)
        <div class="card card-pesanan mb-3">
            <div class="card-body d-flex align-items-center"> {{-- Tambah d-flex dan align-items-center --}}
                {{-- Foto Produk --}}
                <img src="{{ asset('storage/' . ($order->menu->gambar ?? 'default_product.png')) }}"
                     alt="{{ $order->menu->nama }}" class="product-image">

                <div> {{-- Konten teks --}}
                    <h6 class="fw-bold mb-2">{{ $order->menu->nama }}</h6> 
                    <p class="mb-1">Jumlah: {{ $order->jumlah }}</p> 
                    <p class="mb-0">Status:
                        <span class="badge bg-secondary badge-status
                            @if($order->status == 'Selesai') bg-success
                            @elseif ($order->status == 'Dibatalkan') bg-danger
                            @else bg-secondary @endif">
                            {{ $order->status }}
                        </span>
                    </p>
                </div>

                @if($order->status == 'Selesai')
                    <div class="ms-auto"> {{-- Tombol di paling kanan --}}
                        {{-- Cek apakah pesanan sudah diulas oleh user ini --}}
                        @php
                            $hasReviewed = $order->reviews->where('user_id', Auth::id())->isNotEmpty();
                        @endphp

                        @if($hasReviewed)
                            <a href="{{ route('reviews.show_product', $order->menu->id) }}" class="btn btn-info btn-sm me-2">Lihat Ulasan</a>
                        @else
                            {{-- Tombol Ulasan akan memicu modal --}}
                            <button type="button" class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $order->id }}">Ulasan</button>
                        @endif
                        <form action="{{ route('orders.reorder', $order->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm">Beli Lagi</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        {{-- Modal Ulasan untuk setiap pesanan (ditempatkan di dalam loop @forelse) --}}
        <div class="modal fade" id="reviewModal{{ $order->id }}" tabindex="-1" aria-labelledby="reviewModalLabel{{ $order->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="reviewModalLabel{{ $order->id }}">Beri Ulasan untuk {{ $order->menu->nama }}</h5> 
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                            <input type="hidden" name="menu_id" value="{{ $order->menu->id }}">

                            <div class="mb-3">
                                <label for="rating{{ $order->id }}" class="form-label">Rating Bintang:</label>
                                <div id="star-rating-{{ $order->id }}" class="star-rating">
                                    {{-- Bintang akan di-generate dengan JavaScript atau CSS --}}
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="star" data-value="{{ $i }}">&#9733;</span>
                                    @endfor
                                    <input type="hidden" name="rating" id="rating{{ $order->id }}" value="0" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="comment{{ $order->id }}" class="form-label">Ulasan Anda:</label> 
                                <textarea class="form-control" name="comment" id="comment{{ $order->id }}" rows="3" placeholder="Tulis ulasan Anda..." required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="image{{ $order->id }}" class="form-label">Gambar Pesanan (Opsional):</label> 
                                <input type="file" class="form-control" name="image" id="image{{ $order->id }}" accept="image/*">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info text-center">
            Belum ada riwayat pesanan. 
        </div>
    @endforelse
</main>

{{-- Tambahkan script Bootstrap JS dan custom JS untuk rating bintang dan modal --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.star-rating').forEach(function(starRatingContainer) {
            const stars = starRatingContainer.querySelectorAll('.star');
            const hiddenInput = starRatingContainer.querySelector('input[type="hidden"]');

            stars.forEach(function(star) {
                star.addEventListener('click', function() {
                    const value = parseInt(this.dataset.value);
                    hiddenInput.value = value;
                    stars.forEach(s => {
                        if (parseInt(s.dataset.value) <= value) {
                            s.style.color = 'gold';
                        } else {
                            s.style.color = 'gray';
                        }
                    });
                });

                star.addEventListener('mouseover', function() {
                    const value = parseInt(this.dataset.value);
                    stars.forEach(s => {
                        if (parseInt(s.dataset.value) <= value) {
                            s.style.color = 'orange'; // Warna saat hover
                        } else {
                            s.style.color = 'gray';
                        }
                    });
                });

                star.addEventListener('mouseout', function() {
                    const currentValue = parseInt(hiddenInput.value);
                    stars.forEach(s => {
                        if (parseInt(s.dataset.value) <= currentValue) {
                            s.style.color = 'gold';
                        } else {
                            s.style.color = 'gray';
                        }
                    });
                });
            });
        });
    });
</script>
</body>
</html>
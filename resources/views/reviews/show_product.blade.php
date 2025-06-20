<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $menu->nama }} - Ulasan Produk</title>
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
        .card-ulasan {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .product-image-detail {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 20px;
        }
        .star-rating .star {
            color: gold;
            font-size: 1.5rem;
            margin-right: 2px;
        }
        .review-item {
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        .review-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark topbar">
    <div class="container">
        <span class="navbar-brand fw-bold text-white">Detail Produk</span>
        <a href="{{ route('order.history') }}" class="btn btn-light btn-sm">Kembali ke Riwayat Pesanan</a>
    </div>
</nav>

<main class="container my-5">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card card-ulasan mb-4">
        <div class="card-body d-flex align-items-center">
            <img src="{{ asset('storage/' . ($menu->image_path ?? 'default_product.png')) }}"
                 alt="{{ $menu->nama }}" class="product-image-detail">
            <div>
                <h3 class="fw-bold mb-1">{{ $menu->nama }}</h3> 
                <p class="text-muted">Rp{{ number_format($menu->harga, 0, ',', '.') }}</p> 
                <p class="mb-0">Minuman yang yang dengan inkatur yang cukup kernal</p> {{-- Deskripsi produk --}} 
                <p class="mb-0">Penjual: {{ $menu->nama }}</p> {{-- Nama Penjual --}} 
            </div>
        </div>
    </div>

    <div class="card card-ulasan">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Penilaian produk ({{ $menu->reviews->count() ?? 0 }} Ulasan)</h5> 

            {{-- Ulasan Pengguna yang Sedang Login (jika ada) --}}
            @if($userReview)
                <div class="review-item">
                    <h6 class="fw-bold">Ulasan Anda</h6> 
                    <div class="star-rating mb-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="star" style="color: {{ $i <= $userReview->rating ? 'gold' : 'gray' }};">&#9733;</span>
                        @endfor
                    </div>
                    <p>{{ $userReview->comment }}</p>
                    @if ($userReview->image_path)
                        <img src="{{ asset('storage/' . $userReview->image_path) }}" alt="Gambar Ulasan" style="max-width: 150px; height: auto; margin-top: 10px;">
                    @endif
                    <p class="text-muted small mb-2">Tanggal: {{ $userReview->created_at->format('d M Y H:i') }}</p>
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('reviews.edit', $userReview->id) }}" class="btn btn-sm btn-outline-primary me-2">Edit</a> 
                        <form action="{{ route('reviews.destroy', $userReview->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini?');"> 
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button> 
                        </form>
                    </div>
                </div>
            @endif

            {{-- Ulasan dari Pengguna Lain --}}
            @forelse($otherReviews as $review)
                <div class="review-item">
                    <h6 class="fw-bold">{{ $review->user->name }}</h6> 
                    <div class="star-rating mb-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="star" style="color: {{ $i <= $review->rating ? 'gold' : 'gray' }};">&#9733;</span>
                        @endfor
                    </div>
                    <p>{{ $review->comment }}</p>
                    @if ($review->image_path)
                        <img src="{{ asset('storage/' . $review->image_path) }}" alt="Gambar Ulasan" style="max-width: 150px; height: auto; margin-top: 10px;">
                    @endif
                    <p class="text-muted small mb-2">Tanggal: {{ $review->created_at->format('d M Y H:i') }}</p>
                </div>
            @empty
                @if(!$userReview) {{-- Jika belum ada ulasan sama sekali --}}
                    <p class="text-center text-muted">Belum ada ulasan untuk produk ini.</p>
                @endif
            @endforelse
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
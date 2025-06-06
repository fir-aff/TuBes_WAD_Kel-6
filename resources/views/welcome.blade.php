<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kantin Kampus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f5fa;
        }
        .category-tag {
            font-size: 0.75rem;
            font-weight: bold;
            color: white;
            padding: 2px 6px;
            border-radius: 5px;
            position: absolute;
            top: 8px;
            left: 8px;
        }
        .card-menu {
            border-radius: 15px;
            padding: 10px;
            color: #333;
            position: relative;
            overflow: hidden;
        }
        .card-menu .title {
            font-weight: 600;
            margin-top: 5px;
        }
        .badge-minuman { background: #7f9cf5; }
        .badge-makanan { background: #f56565; }
        .badge-camilan  { background: #f6ad55; }
        .badge-dessert  { background: #ed64a6; }
    </style>
</head>
<body>

<nav class="navbar navbar-light bg-primary text-white px-4">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1 text-white">🍴 Kantin Kampus</span>
        <form class="d-flex w-50">
            <input class="form-control" type="search" placeholder="Cari makanan atau minuman..." aria-label="Search">
        </form>
        <div class="d-flex align-items-center gap-3">
            🛒 Keranjang (0)

            @guest
                <a href="{{ route('login') }}" class="btn btn-light btn-sm">Login</a>
            @endguest
            @auth
                <span class="text-white">{{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Logout</button>
                </form>
            @endauth
        </div>
    </div>
</nav>

<div class="container my-4">
    <div class="p-4 rounded text-white" style="background: linear-gradient(135deg, #7f00ff, #7c43bd);">
        <h4>Selamat Datang di Kantin Kampus!</h4>
        <p>Pesan makanan favoritmu dengan mudah dan cepat.</p>
        <button class="btn btn-light btn-sm text-primary">Promo Hari Ini</button>
    </div>

    <div class="mt-4">
        <span class="badge bg-secondary">Semua</span>
        <span class="badge bg-info text-dark">Minuman</span>
        <span class="badge bg-danger">Makanan Berat</span>
        <span class="badge bg-warning text-dark">Camilan</span>
        <span class="badge bg-pink">Dessert</span>
    </div>

    <h5 class="mt-4 mb-3">Semua Menu</h5>

    <div class="row g-4">
        @foreach ($menus as $menu)
        @php
            $kategori = strtolower($menu->kategori);
            $warna = match($kategori) {
                'minuman' => 'badge-minuman',
                'makanan berat' => 'badge-makanan',
                'camilan' => 'badge-camilan',
                'dessert' => 'badge-dessert',
                default => 'bg-secondary'
            };
        @endphp
        <div class="col-md-3">
            <div class="bg-white card-menu shadow-sm p-3">
                <span class="category-tag {{ $warna }}">{{ ucfirst($menu->kategori) }}</span>
                @if ($menu->gambar)
                    <img src="{{ asset('storage/menu/' . $menu->gambar) }}" class="w-100 mb-2 rounded" style="height: 150px; object-fit: cover;">
                @endif
                <div class="title">{{ $menu->nama }}</div>
                <small class="text-muted d-block mb-2">{{ $menu->deskripsi }}</small>
                <strong class="text-primary">Rp {{ number_format($menu->harga, 0, ',', '.') }}</strong>
                <div class="text-end mt-2">
                    <button class="btn btn-sm btn-outline-primary">+</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

</body>
</html>

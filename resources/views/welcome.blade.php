<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kantin Kampus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f5fa; }
        .category-tag { font-size: 0.75rem; font-weight: bold; color: white; padding: 2px 6px; border-radius: 5px; position: absolute; top: 8px; left: 8px; }
        .card-menu { border-radius: 15px; padding: 10px; color: #333; position: relative; overflow: hidden; }
        .card-menu .title { font-weight: 600; margin-top: 5px; }
        .badge-minuman { background: #7f9cf5; }
        .badge-makanan { background: #f56565; }
        .badge-camilan  { background: #f6ad55; }
        .badge-dessert  { background: #ed64a6; }
        .btn-kategori.active { background-color: #4a148c !important; color: white !important; font-weight: bold; }
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
            <a href="{{ route('cart.show') }}" class="btn btn-light position-relative">
                🛒 Keranjang
                @php
                    $cartCount = count(session('cart', []));
                @endphp
                @if($cartCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ $cartCount }}
                    <span class="visually-hidden">items in cart</span>
                </span>
                @endif
            </a>

            @guest
                <a href="{{ route('login') }}" class="btn btn-light btn-sm">Login</a>
            @endguest
            @auth
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        {{ auth()->user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil Saya</a></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Yakin ingin logout?')">
                                @csrf
                                <button class="dropdown-item text-danger" type="submit">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth
        </div>
    </div>
</nav>

<div class="container my-4">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="p-4 rounded text-white" style="background: linear-gradient(135deg, #7f00ff, #7c43bd);">
        <h4>Selamat Datang di Kantin Kampus!</h4>
        <p>Pesan makanan favoritmu dengan mudah dan cepat.</p>
        <button class="btn btn-light btn-sm text-primary">Promo Hari Ini</button>
    </div>
    
    {{-- Tombol Filter Kategori --}}
    <div class="mt-4 d-flex gap-2">
        @foreach($kategoriList as $kategori)
            <a href="{{ route('welcome', ['kategori' => $kategori]) }}" 
            class="btn btn-sm btn-outline-primary btn-kategori {{ ($kategoriPilihan ?? 'Semua') == $kategori ? 'active' : '' }}">
                {{ $kategori }}
            </a>
        @endforeach
    </div>

    <h5 class="mt-4 mb-3">
        @if($kategoriPilihan && $kategoriPilihan !== 'Semua')
            Kategori: {{$kategoriPilihan}}
        @else
            Semua Menu
        @endif
    </h5>

    <div class="row g-4">
        @forelse ($menus as $menu)
        @php
            $kategori_menu = strtolower($menu->kategori);
            $warna = match($kategori_menu) {
                'minuman' => 'badge-minuman',
                'makanan berat' => 'badge-makanan',
                'camilan' => 'badge-camilan',
                'dessert' => 'badge-dessert',
                default => 'bg-secondary',
            };
        @endphp
        <div class="col-md-3">
            <div class="bg-white card-menu shadow-sm p-3">
                <span class="category-tag {{ $warna }}">{{ ucfirst($menu->kategori) }}</span>
                @if ($menu->gambar)
                    <img src="{{ asset('storage/menu/' . $menu->gambar) }}" class="w-100 mb-2 rounded" style="height: 150px; object-fit: cover;">
                @endif
                <div class="card-body d-flex flex-column">
                    <div class="title">{{ $menu->nama }}</div>
                    <small class="text-muted d-block mb-2">{{ $menu->deskripsi }}</small>
                    <strong class="text-primary">Rp {{ number_format($menu->harga, 0, ',', '.') }}</strong>
                    <form action="{{ route('cart.add') }}" method="POST" class="d-grid">
                        @csrf
                        <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                        <input type="hidden" name="nama" value="{{ $menu->nama }}">
                        <input type="hidden" name="harga" value="{{ $menu->harga }}">
                        <input type="hidden" name="gambar" value="{{ $menu->gambar }}">
                        <button type="submit" class="btn btn-primary">+</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-secondary text-center">
                Tidak ada menu yang tersedia untuk kategori ini.
            </div>
        </div>
        @endforelse
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
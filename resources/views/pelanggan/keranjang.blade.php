<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Keranjang - Kantin Kampus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; }
        .navbar { background-color: #4a148c; }
        .payment-card { max-width: 700px; margin: 40px auto; }
        .quantity-input { width: 60px; }
        .item-subtotal {
            width: 120px; /* Beri lebar agar sejajar */
            text-align: right;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark px-4">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">Pembayaran Kantin</span>
        <a href="{{ route('welcome') }}" class="btn btn-light btn-sm">Kembali ke Menu</a>
    </div>
</nav>

<div class="container">
    <div class="card shadow-sm border-0 p-4 p-md-5 payment-card">
        <h4 class="mb-4">Detail Pesanan</h4>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        
        @forelse ($cartItems as $id => $item)
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    @if(isset($item['gambar']))
                    <img src="{{ asset('storage/menu/' . $item['gambar']) }}" width="60" class="rounded me-3">
                    @endif
                    <div>
                        <h6 class="mb-0">{{ $item['nama'] }}</h6>
                        <small class="text-muted">Rp {{ number_format($item['harga'], 0, ',', '.') }}</small>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <form action="{{ route('cart.update') }}" method="POST" class="me-2">
                        @csrf
                        <input type="hidden" name="menu_id" value="{{ $id }}">
                        <input type="number" name="kuantitas" value="{{ $item['kuantitas'] }}" class="form-control form-control-sm quantity-input" onchange="this.form.submit()">
                    </form>
                    <div class="item-subtotal me-3">
                        <span class="fw-bold">Rp {{ number_format($item['harga'] * $item['kuantitas'], 0, ',', '.') }}</span>
                    </div>
                    <form action="{{ route('cart.remove') }}" method="POST">
                        @csrf
                        <input type="hidden" name="menu_id" value="{{ $id }}">
                        <button type="submit" class="btn btn-danger btn-sm">×</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="alert alert-secondary text-center">
                Keranjang Anda masih kosong. Silakan <a href="{{ route('welcome') }}">pilih menu</a>.
            </div>
        @endforelse
        
        @if(count($cartItems) > 0)
        <hr class="my-3">
        <div class="d-flex justify-content-between fw-bold mb-4">
            <h5 class="mb-0">Total Pembayaran</h5>
            <h5 class="mb-0 text-primary">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</h5>
        </div>
        {{-- Form Metode Pembayaran dan Tombol Bayar --}}
        <form action="{{-- Arahkan ke route proses pembayaran --}}" method="POST">
            @csrf
            {{-- ... (Opsi metode pembayaran) ... --}}
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary btn-lg">Bayar Sekarang</button>
            </div>
        </form>
        @endif
    </div>
</div>
</body>
</html>
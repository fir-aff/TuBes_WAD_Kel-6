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
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark topbar">
    <div class="container">
        <span class="navbar-brand fw-bold text-white">🍽️ Riwayat Pesanan</span>
        <a href="{{ route('welcome') }}" class="btn btn-light btn-sm">← Kembali ke Beranda</a>
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
            <div class="card-body">
                <h6 class="fw-bold mb-2">🍔 {{ $order->menu->nama }}</h6>
                <p class="mb-1">Jumlah: {{ $order->jumlah }}</p>
                <p class="mb-0">Status: 
                    <span class="badge bg-secondary badge-status">{{ $order->status }}</span>
                </p>
            </div>
        </div>
    @empty
        <div class="alert alert-info text-center">
            Belum ada riwayat pesanan.
        </div>
    @endforelse
</main>

</body>
</html>

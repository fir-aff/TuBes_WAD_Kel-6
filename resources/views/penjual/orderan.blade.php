<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Orderan Masuk - Penjual</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #7f00ff, #7c43bd);
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }
        .navbar {
            background-color: #4a148c;
        }
        .order-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            padding: 1.5rem;
        }
        .btn-purple {
            background-color: #6b21a8;
            color: white;
        }
        .btn-purple:hover {
            background-color: #581c87;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark px-4">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">📦 Orderan Masuk</span>
        <a href="{{ route('manajemen') }}" class="btn btn-light btn-sm">🍽️ Kembali ke Manajemen</a>
    </div>
</nav>

<div class="container mt-4">
    <h3 class="text-white mb-4 fw-semibold">Daftar Pesanan</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse($orders as $order)
    <div class="order-card mb-3">
        <div class="row">
            <div class="col-md-9">
                <p class="mb-1"><strong>Pelanggan:</strong> {{ $order->user->name }}</p>
                <p class="mb-1"><strong>Menu:</strong> {{ $order->menu->nama }}</p>
                <p class="mb-1"><strong>Jumlah:</strong> {{ $order->jumlah }}</p>
                <p class="mb-0"><strong>Status:</strong> 
                    <span class="badge 
                        @if($order->status == 'Selesai') bg-success 
                        @elseif($order->status == 'Dibatalkan') bg-danger 
                        @else bg-secondary @endif">
                        {{ $order->status }}
                    </span>
                </p>
            </div>
            <div class="col-md-3 d-flex align-items-center justify-content-end">
                @if($order->status === 'Menunggu Konfirmasi')
                <form action="{{ route('order.selesaikan', $order->id) }}" method="POST" class="me-2">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">✔️ Selesai</button>
                </form>
                <form action="{{ route('order.batal', $order->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">✖️ Batal</button>
                </form>
                @endif
                <form action="{{ route('order.hapus', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm ms-2">🗑️ Hapus</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="alert alert-light text-center">Belum ada pesanan masuk.</div>
    @endforelse
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Orderan Masuk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3 class="mb-4">Daftar Pesanan Masuk</h3>

    @foreach($orders as $order)
    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Pelanggan:</strong> {{ $order->user->name }}</p>
            <p><strong>Menu:</strong> {{ $order->menu->nama }}</p>
            <p><strong>Jumlah:</strong> {{ $order->jumlah }}</p>
            <p><strong>Status:</strong> <span class="badge bg-secondary">{{ $order->status }}</span></p>

            @if($order->status === 'Menunggu Konfirmasi')
                <form action="{{ route('order.selesaikan', $order->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-success btn-sm">Selesaikan</button>
                </form>
                <form action="{{ route('order.batal', $order->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-danger btn-sm">Batalkan</button>
                </form>
            @endif
        </div>
    </div>
    @endforeach

    @if($orders->isEmpty())
        <div class="alert alert-info">Belum ada pesanan masuk.</div>
    @endif
</div>
</body>
</html>

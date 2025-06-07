<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pesanan Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h3 class="mb-4">Riwayat Pesanan</h3>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


        @foreach($orders as $order)
        <div class="card mb-3">
            <div class="card-body">
                <p><strong>Menu:</strong> {{ $order->menu->nama }}</p>
                <p><strong>Jumlah:</strong> {{ $order->jumlah }}</p>
                <p><strong>Status:</strong> <span class="badge bg-secondary">{{ $order->status }}</span></p>
            </div>
        </div>
        @endforeach

        @if(count($orders) === 0)
        <div class="alert alert-info text-center">Belum ada riwayat pesanan.</div>
        @endif
    </div>
</body>
</html>

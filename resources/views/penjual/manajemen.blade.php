<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Kantin Kampus</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #7f00ff, #7c43bd);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .card-container {
            background: white;
            padding: 3rem;
            border-radius: 1.5rem;
            text-align: center;
            width: 100%;
            max-width: 750px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .role-box {
            border-radius: 1rem;
            padding: 1.5rem;
            transition: 0.3s ease-in-out;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }
        .role-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        .footer {
            font-size: 0.9rem;
            color: #888;
            margin-top: 2rem;
        }
    </style>
</head>
<body>

<div class="card-container">
    <h4 class="fw-bold mb-2">🍽️ Selamat Datang di Manajemen Kantin Kampus</h4>
    <p class="text-muted mb-4">Silakan pilih</p>

    <div class="row g-3 justify-content-center">
        <div class="col-md-4 d-flex">
            <a href="{{ route('menu') }}" class="role-box d-block bg-danger-subtle text-center text-decoration-none w-100 h-100">
                <div class="fs-1">👤</div>
                <h6 class="fw-bold mt-2">Menambahkan Produk</h6>
                <p class="text-muted small">Menambahkan, mengupdate, menghapus produk</p>
            </a>
        </div>
        <div class="col-md-4 d-flex">
            <a href="{{ route('order.index') }}" class="role-box d-block bg-primary-subtle text-center text-decoration-none w-100 h-100">
                <div class="fs-1">🧑‍🍳</div>
                <h6 class="fw-bold mt-2">Pesanan</h6>
                <p class="text-muted small">Kelola pesanan dari pelanggan</p>
            </a>
        </div>
    </div>


    <div class="footer mt-4">
        © {{ now()->year }} Kantin Kampus. All rights reserved.
    </div>
</div>

</body>
</html>

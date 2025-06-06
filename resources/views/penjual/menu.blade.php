<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Menu - Kantin Kampus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .navbar {
            background: linear-gradient(to right, #7f00ff, #7c43bd);
        }
        .btn-purple {
            background-color: #7f00ff;
            color: white;
        }
        .btn-purple:hover {
            background-color: #6610f2;
            color: white;
        }
    </style>
</head>
<body>

<!-- Header -->
<nav class="navbar navbar-expand-lg navbar-dark px-4 py-3 shadow">
    <div class="container-fluid">
        <span class="navbar-brand fw-bold">🍴 Kantin Kampus - Manajemen Menu</span>
        <a href="/" class="btn btn-light btn-sm">← Kembali ke Beranda</a>
    </div>
</nav>

<div class="container mt-4">

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0 fw-semibold text-primary">Tambah Menu</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ url('/penjual/menu') }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Menu</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select" required>
                            <option value="Minuman">Minuman</option>
                            <option value="Makanan Berat">Makanan Berat</option>
                            <option value="Camilan">Camilan</option>
                            <option value="Dessert">Dessert</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Harga</label>
                        <input type="number" name="harga" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Gambar</label>
                        <input type="file" name="gambar" class="form-control">
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-purple">Simpan Menu</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0 fw-semibold text-primary">Daftar Menu</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($menus as $menu)
                    <tr>
                        <td>{{ $menu->nama }}</td>
                        <td>{{ $menu->kategori }}</td>
                        <td>Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $menu->id }}">Edit</button>

                            <form method="POST" action="{{ url('/penjual/menu/' . $menu->id) }}" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal{{ $menu->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ url('/penjual/menu/' . $menu->id) }}" enctype="multipart/form-data">
                                @csrf @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Menu</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-2">
                                            <label class="form-label">Nama</label>
                                            <input type="text" name="nama" class="form-control" value="{{ $menu->nama }}" required>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Kategori</label>
                                            <select name="kategori" class="form-select" required>
                                                <option value="Minuman" @selected($menu->kategori == 'Minuman')>Minuman</option>
                                                <option value="Makanan Berat" @selected($menu->kategori == 'Makanan Berat')>Makanan Berat</option>
                                                <option value="Camilan" @selected($menu->kategori == 'Camilan')>Camilan</option>
                                                <option value="Dessert" @selected($menu->kategori == 'Dessert')>Dessert</option>
                                            </select>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Harga</label>
                                            <input type="number" name="harga" class="form-control" value="{{ $menu->harga }}" required>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Deskripsi</label>
                                            <textarea name="deskripsi" class="form-control">{{ $menu->deskripsi }}</textarea>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Gambar Baru</label>
                                            <input type="file" name="gambar" class="form-control">
                                            @if ($menu->gambar)
                                                <img src="{{ asset('storage/menu/' . $menu->gambar) }}" width="100" class="mt-2">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary">Simpan</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

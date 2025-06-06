<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kelola Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-4">
    <h2>Daftar Menu Saya</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ url('/penjual/menu') }}" method="POST" class="mb-4" enctype="multipart/form-data">
        @csrf
        <div class="row g-2">
            <div class="col-md-3"><input type="text" name="nama" class="form-control" placeholder="Nama menu" required></div>
            <div class="col-md-2">
                <select name="kategori" class="form-control" required>
                    <option value="makanan">Makanan</option>
                    <option value="minuman">Minuman</option>
                </select>
            </div>
            <div class="col-md-2"><input type="number" name="harga" class="form-control" placeholder="Harga" required></div>
            <div class="col-md-3"><input type="text" name="deskripsi" class="form-control" placeholder="Deskripsi opsional"></div>
            <div class="col-md-2">
                <input type="file" name="gambar" class="form-control">
            </div>
            <div class="col-md-12 mt-2">
                <button class="btn btn-primary">Tambah</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Deskripsi</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($menus as $menu)
            <tr>
                <td>{{ $menu->nama }}</td>
                <td>{{ ucfirst($menu->kategori) }}</td>
                <td>{{ $menu->harga }}</td>
                <td>{{ $menu->deskripsi }}</td>
                <td>
                    @if ($menu->gambar)
                        <img src="{{ asset('storage/menu/' . $menu->gambar) }}" width="80">
                    @endif
                </td>
                <td>
                    <form action="{{ url('/penjual/menu/' . $menu->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </form>

                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $menu->id }}">Edit</button>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal{{ $menu->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $menu->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ url('/penjual/menu/' . $menu->id) }}" enctype="multipart/form-data">
                                @csrf @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Menu</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-2">
                                            <label>Nama</label>
                                            <input type="text" name="nama" class="form-control" value="{{ $menu->nama }}" required>
                                        </div>
                                        <div class="mb-2">
                                            <label>Kategori</label>
                                            <select name="kategori" class="form-control" required>
                                                <option value="makanan" {{ $menu->kategori == 'makanan' ? 'selected' : '' }}>Makanan</option>
                                                <option value="minuman" {{ $menu->kategori == 'minuman' ? 'selected' : '' }}>Minuman</option>
                                            </select>
                                        </div>
                                        <div class="mb-2">
                                            <label>Harga</label>
                                            <input type="number" name="harga" class="form-control" value="{{ $menu->harga }}" required>
                                        </div>
                                        <div class="mb-2">
                                            <label>Deskripsi</label>
                                            <textarea name="deskripsi" class="form-control">{{ $menu->deskripsi }}</textarea>
                                        </div>
                                        <div class="mb-2">
                                            <label>Gambar Baru</label>
                                            <input type="file" name="gambar" class="form-control">
                                            @if ($menu->gambar)
                                                <p class="mt-2">Gambar saat ini:<br>
                                                <img src="{{ asset('storage/menu/' . $menu->gambar) }}" width="100"></p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- End Modal -->
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</body>
</html>

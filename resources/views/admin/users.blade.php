<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pengguna - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #7f00ff, #7c43bd);
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem;
        }

        .navbar-card {
            background: #fff;
            padding: 1rem 2rem;
            border-radius: 1rem;
            width: 100%;
            max-width: 1000px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .admin-card {
            background: white;
            padding: 2rem;
            border-radius: 1.5rem;
            width: 100%;
            max-width: 1000px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .badge-role {
            text-transform: uppercase;
            font-size: 0.75rem;
        }

        table th, table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>

<div class="navbar-card">
    <h5 class="fw-bold mb-0">🧑‍💼 Admin - Kelola Pengguna</h5>
    <div class="d-flex gap-2">
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">Logout</button>
        </form>
    </div>
</div>

<div class="admin-card">
    <h5 class="fw-bold mb-4">📋 Daftar Seluruh Pengguna</h5>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered text-center">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role Sekarang</th>
                    <th>Ubah Role</th>
                    <th>Aksi</th> <!-- Tambahkan kolom ini -->
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge bg-secondary badge-role">{{ $user->role }}</span>
                    </td>
                    <td>
                        @if (auth()->id() !== $user->id)
                            <form action="{{ url('/admin/users/' . $user->id . '/promote') }}" method="POST" class="d-flex justify-content-center gap-2">
                                @csrf
                                <select name="role" class="form-select form-select-sm w-auto">
                                    <option value="pelanggan" {{ $user->role == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                                    <option value="penjual" {{ $user->role == 'penjual' ? 'selected' : '' }}>Penjual</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary">Ubah</button>
                            </form>
                        @else
                            <em class="text-muted small">(tidak bisa ubah role sendiri)</em>
                        @endif
                    </td>
                    <td>
                        @if (auth()->id() !== $user->id)
                            <form action="{{ url('/admin/users/' . $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>

</body>
</html>

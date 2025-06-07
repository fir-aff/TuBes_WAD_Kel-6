<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil - Kantin Kampus</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #7f00ff, #7c43bd);
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .profile-card {
            background: white;
            border-radius: 1.5rem;
            padding: 2rem;
            width: 100%;
            max-width: 550px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .btn-purple {
            background-color: #6b21a8;
            color: white;
        }
        .btn-purple:hover {
            background-color: #581c87;
        }
        .btn-yellow {
            background-color: #facc15;
            color: #333;
        }
        .btn-yellow:hover {
            background-color: #eab308;
        }
    </style>
</head>
<body>

<div class="profile-card">
    <h4 class="text-center fw-bold text-primary mb-4">👤 Edit Profil</h4>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" class="mb-4">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
        </div>

        <button type="submit" class="btn btn-purple w-100">💾 Simpan Perubahan</button>
    </form>

    <hr class="my-4">

    <h5 class="mb-3">🔐 Ubah Password</h5>
    <form action="{{ route('profile.password') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Password Saat Ini</label>
            <input type="password" name="current_password" class="form-control">
            @error('current_password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Password Baru</label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <button type="submit" class="btn btn-yellow w-100 mb-2">🔁 Ubah Password</button>

        <a href="{{ route('welcome') }}" class="btn btn-purple w-100 text-white">🏠 Kembali ke Beranda</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

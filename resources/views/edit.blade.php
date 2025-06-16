<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil - Kantin Kampus</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            /* Menyesuaikan gradient agar lebih sesuai dengan gambar */
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .profile-card {
            background: white;
            border-radius: 1.25rem; /* Sedikit menyesuaikan border-radius */
            padding: 2.5rem; /* Menambah padding agar lebih lega */
            width: 100%;
            max-width: 550px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            position: relative;
        }
        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 1.5rem;
            color: #333;
            text-decoration: none;
        }
        .back-btn:hover {
            color: #6a11cb;
        }
        .profile-header {
            text-align: center;
            margin-bottom: 2rem;
            color: #333;
            font-weight: 600;
        }
        .section-title {
            font-weight: 600;
            color: #555;
            margin-bottom: 1rem;
        }
        .form-label {
            color: #555;
            font-weight: 500;
        }
        .form-control {
            border-radius: 0.5rem;
            border: 1px solid #ddd;
        }
        .form-control:focus {
            border-color: #8A2BE2;
            box-shadow: 0 0 0 0.2rem rgba(138, 43, 226, 0.25);
        }
        /* Menyesuaikan warna tombol agar lebih cocok */
        .btn-purple {
            background-color: #5e35b1;
            color: white;
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem;
            font-weight: 500;
        }
        .btn-purple:hover {
            background-color: #4527a0;
            color: white;
        }
        .btn-yellow {
            background-color: #fdd835;
            color: #333;
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem;
            font-weight: 500;
        }
        .btn-yellow:hover {
            background-color: #fbc02d;
            color: #333;
        }
        .btn i {
            margin-right: 8px; /* Jarak antara ikon dan teks */
        }
    </style>
</head>
<body>

<div class="profile-card">
    <a href="{{ route('welcome') }}" class="back-btn"><i class="fas fa-arrow-left"></i></a>

    <h4 class="profile-header"><i class="fas fa-user-edit"></i> Edit Profil</h4>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" class="mb-4">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
        </div>

        <button type="submit" class="btn btn-purple w-100 mt-3">
            <i class="fas fa-save"></i> Simpan Perubahan
        </button>
    </form>

    <div class="mt-4">
        <h5 class="section-title"><i class="fas fa-lock"></i> Ubah Password</h5>
        <form action="{{ route('profile.password') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="current_password" class="form-label">Password Saat Ini</label>
                <input type="password" id="current_password" name="current_password" class="form-control">
                @error('current_password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password Baru</label>
                <input type="password" id="password" name="password" class="form-control">
                 @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
            </div>

            <button type="submit" class="btn btn-yellow w-100">
                <i class="fas fa-key"></i> Ubah Password
            </button>
        </form>
    </div>

    <div class="mt-3">
        <a href="{{ route('order.history') }}" class="btn btn-purple w-100"> <i class="fas fa-history"></i> Riwayat Pembelian
        </a>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
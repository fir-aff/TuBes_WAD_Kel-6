<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar - Kantin Kampus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #7f00ff, #7c43bd);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            border: none;
            border-radius: 1rem;
            padding: 2rem;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .text-brand {
            font-weight: bold;
            color: white;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        .btn-register {
            background-color: #4f46e5;
            color: white;
        }
        .btn-register:hover {
            background-color: #4338ca;
        }
        label {
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="text-center position-absolute top-0 w-100 mt-4">
    <div class="text-brand">
        🍴 Kantin Kampus
    </div>
</div>

<div class="card bg-white">
    <h4 class="text-center mb-4 text-primary fw-bold">Buat Akun Baru</h4>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label for="name">Nama Lengkap</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Nama lengkap" required autofocus>
        </div>

        <div class="mb-3">
            <label for="email">Email / Username</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Email atau username" required>
        </div>

        <div class="mb-3">
            <label for="password">Kata Sandi</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Kata sandi" required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation">Konfirmasi Sandi</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi kata sandi" required>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-register">Daftar</button>
        </div>

        <div class="text-center mt-3">
            Sudah punya akun? <a href="{{ route('login') }}">Daftar di sini</a>
        </div>

        <div class="text-center mt-3">

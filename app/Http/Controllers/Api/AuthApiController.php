<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthApiController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            // 'role' => 'nullable|string|in:pembeli,penjual', // Contoh role
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // 'role' => $request->role ?? 'pembeli', // Default role
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        // 1. Validasi Kredensial
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Cari Pengguna Berdasarkan Email
        $user = User::where('email', $credentials['email'])->first();

        // 3. Verifikasi Kredensial (Pengguna ada dan Password cocok)
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            // Mengembalikan respons JSON dengan pesan kesalahan yang sesuai
            throw ValidationException::withMessages([
                'email' => ['Kredensial tidak cocok dengan catatan kami.'],
            ]);
        }

        // 4. Buat Token Otentikasi
        // Pastikan Anda telah menginstal Laravel Sanctum dan menjalankan migrasi
        $token = $user->createToken('auth_token')->plainTextToken;

        // 5. Kembalikan Respons Sukses
        return response()->json([
            'message' => 'Login berhasil!',
            'access_token' => $token,
            'token_type' => 'Bearer',
            // Anda bisa memilih data user mana yang ingin Anda kembalikan
            // Misalnya, hanya ID, nama, dan email untuk keamanan
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                // Tambahkan atribut lain yang relevan jika diperlukan
                'role' => $user->role, // Jika Anda ingin mengembalikan role juga
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete(); // Hapus token saat ini

        return response()->json([
            'message' => 'Successfully logged out and token deleted.'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
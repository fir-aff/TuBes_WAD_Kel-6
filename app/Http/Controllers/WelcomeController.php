<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu; // Import model Menu

class WelcomeController extends Controller
{
    /**
     * Menampilkan halaman utama dengan semua menu.
     */
    public function index(Request $request)
    {
        // Ambil nilai 'kategori' dan 'q' (search) dari query string
        $kategoriPilihan = $request->query('kategori');
        $q = trim((string) $request->query('q', ''));

        // Siapkan query builder untuk model Menu
        $query = Menu::query();

        // Filter berdasarkan kategori jika dipilih
        if ($kategoriPilihan && $kategoriPilihan !== 'Semua') {
            $query->where('kategori', $kategoriPilihan);
        }

        // Jika ada query pencarian, filter nama atau deskripsi
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'like', "%{$q}%")
                    ->orWhere('deskripsi', 'like', "%{$q}%");
            });
        }

        // Ambil hasil
        $menus = $query->latest()->get();

        // Daftar kategori untuk tombol filter
        $kategoriList = ['Semua', 'Makanan Berat', 'Minuman', 'Camilan', 'Dessert'];

        // Kirim data ke view
        return view('welcome', [
            'menus' => $menus,
            'kategoriList' => $kategoriList,
            'kategoriPilihan' => $kategoriPilihan ?: 'Semua',
            'q' => $q,
        ]);
    }
}
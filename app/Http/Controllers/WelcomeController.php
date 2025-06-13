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
        // 1. Ambil nilai 'kategori' dari query string di URL
        $kategoriPilihan = $request->query('kategori');

        // 2. Siapkan query builder untuk model Menu
        $query = Menu::query();

        // 3. Jika ada kategori yang dipilih (dan bukan 'Semua'), filter data
        if ($kategoriPilihan && $kategoriPilihan !== 'Semua') {
            $query->where('kategori', $kategoriPilihan);
        }

        // 4. Eksekusi query untuk mendapatkan data menu
        $menus = $query->latest()->get();

        // 5. INI BAGIAN PENTING: Definisikan daftar kategori yang akan menjadi tombol
        $kategoriList = ['Semua', 'Makanan Berat', 'Minuman', 'Camilan', 'Dessert'];

        // 6. Kirim SEMUA data yang diperlukan ke view 'welcome'
        return view('welcome', [
            'menus' => $menus,
            'kategoriList' => $kategoriList,
            'kategoriPilihan' => $kategoriPilihan ?: 'Semua'
        ]);
    }
}
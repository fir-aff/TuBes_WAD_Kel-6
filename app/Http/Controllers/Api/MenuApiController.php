<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Validator; // Tambahkan ini untuk validasi

class MenuApiController extends Controller
{
    /**
     * Menampilkan semua data menu.
     */
    public function index()
    {
        $menus = Menu::all();
        return response()->json([
            'success' => true,
            'message' => 'Daftar semua menu berhasil diambil.',
            'data' => $menus
        ]);
    }

    /**
     * Menampilkan satu data menu berdasarkan ID.
     */
    public function show($id)
    {
        $menu = Menu::find($id);

        if ($menu) {
            return response()->json([
                'success' => true,
                'message' => 'Detail menu berhasil diambil.',
                'data' => $menu
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Menu tidak ditemukan.',
            ], 404);
        }
    }

    /**
     * Menyimpan menu baru.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_menu' => 'required|string|max:255',
            'harga' => 'required|integer',
            'deskripsi' => 'required|string',
            'kategori' => 'required|string',
            // tambahkan validasi lain jika perlu, misal untuk gambar
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Buat menu baru dengan data yang sudah divalidasi
        $menu = Menu::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Menu baru berhasil ditambahkan.',
            'data' => $menu
        ], 201);
    }

    /**
     * Memperbarui data menu berdasarkan ID.
     */
    public function update(Request $request, $id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json(['message' => 'Menu tidak ditemukan'], 404);
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_menu' => 'sometimes|required|string|max:255',
            'harga' => 'sometimes|required|integer',
            'deskripsi' => 'sometimes|required|string',
            'kategori' => 'sometimes|required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update menu dengan data yang sudah divalidasi
        $menu->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil diperbarui.',
            'data' => $menu
        ]);
    }

    /**
     * Menghapus menu berdasarkan ID.
     */
    public function destroy($id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json(['message' => 'Menu tidak ditemukan'], 404);
        }

        $menu->delete();

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil dihapus.'
        ]);
    }
}
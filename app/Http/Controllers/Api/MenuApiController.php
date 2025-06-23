<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Untuk upload gambar
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan seller_user_id

class MenuApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::query();

        // Filter berdasarkan kategori (contoh)
        if ($request->has('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        // Filter berdasarkan nama (contoh)
        if ($request->has('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $menus = $query->latest()->get(); // Atau paginate()

        return response()->json($menus);
    }

    public function show(Menu $menu)
    {
        return response()->json($menu);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'harga' => 'required|numeric|min:0',
            'kategori' => 'required|string|in:makanan berat,minuman,camilan,dessert',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // optional
        ]);

        $imagePath = null;
        if ($request->hasFile('gambar')) {
            $imagePath = $request->file('gambar')->store('menu', 'public');
        }

        $menu = Menu::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'kategori' => $request->kategori,
            'gambar' => $imagePath,
            'seller_user_id' => Auth::id(), // Otomatis set seller_user_id
        ]);

        return response()->json([
            'message' => 'Menu created successfully',
            'menu' => $menu
        ], 201);
    }

    public function update(Request $request, Menu $id)
    {
        // Otorisasi: Pastikan user yang update adalah pemilik menu atau admin
        // if ($request->user()->id !== $menu->seller_user_id && !$request->user()->hasRole('admin')) {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }

        $menu = Menu::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string',
            'harga' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // handle gambar baru (opsional)
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/menu', $filename);
            $data['gambar'] = $filename;
        }

        $menu->update($data);

        return response()->json([
            'message' => 'Menu updated successfully',
            'menu' => $menu
        ]);
    }

    public function destroy(Menu $id)
    {
        // Otorisasi: Pastikan user yang menghapus adalah pemilik menu atau admin
        // if ($request->user()->id !== $menu->seller_user_id && !$request->user()->hasRole('admin')) {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }

        $menu = Menu::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $menu->delete();

        return response()->json(['message' => 'Menu deleted successfully']);
    }
}
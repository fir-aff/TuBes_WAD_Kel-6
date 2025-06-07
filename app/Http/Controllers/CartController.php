<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu; // Pastikan Anda punya model Menu

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang belanja.
     */
    public function show()
    {
        $cartItems = session()->get('cart', []);
        $totalPembayaran = 0;

        foreach ($cartItems as $item) {
            // Pastikan 'harga' dan 'kuantitas' ada sebelum dihitung
            if (isset($item['harga']) && isset($item['kuantitas'])) {
                $totalPembayaran += $item['harga'] * $item['kuantitas'];
            }
        }

        return view('pelanggan.keranjang', [
            'cartItems' => $cartItems,
            'totalPembayaran' => $totalPembayaran
        ]);
    }

    /**
     * Menambahkan item ke keranjang.
     */
    public function add(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'nama' => 'required|string',
            'harga' => 'required|numeric',
        ]);

        $cart = session()->get('cart', []);
        $menuId = $request->menu_id;

        if (isset($cart[$menuId])) {
            // Jika item sudah ada, tambah kuantitasnya
            $cart[$menuId]['kuantitas']++;
        } else {
            // Jika item belum ada, tambahkan ke keranjang
            $cart[$menuId] = [
                "menu_id" => $request->menu_id,
                "nama" => $request->nama,
                "harga" => $request->harga,
                "kuantitas" => 1,
                "gambar" => $request->gambar // Opsional: simpan gambar untuk ditampilkan di keranjang
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Menu berhasil ditambahkan ke keranjang!');
    }

    /**
     * Memperbarui kuantitas item.
     */
    public function update(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'kuantitas' => 'required|numeric|min:1',
        ]);

        $cart = session()->get('cart', []);
        $menuId = $request->menu_id;

        if (isset($cart[$menuId])) {
            $cart[$menuId]['kuantitas'] = $request->kuantitas;
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.show')->with('success', 'Keranjang berhasil diperbarui.');
    }

    /**
     * Menghapus item dari keranjang.
     */
    public function remove(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
        ]);

        $cart = session()->get('cart', []);
        $menuId = $request->menu_id;

        if (isset($cart[$menuId])) {
            unset($cart[$menuId]); // Hapus item dari array
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.show')->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}
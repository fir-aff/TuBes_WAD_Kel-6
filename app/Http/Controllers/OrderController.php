<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Menu;


class OrderController extends Controller
{
    public function store(Request $request)
    {
        $userId = Auth::id();
        $carts = Session()->get('cart', []);

        if (empty($carts)) {
            return redirect()->back()->with('error', 'Keranjang kosong!');
        }

        foreach ($carts as $menuId => $item) {
            $menu = Menu::find($menuId);

            if (!$menu) {
                // Log atau tangani jika menu tidak ditemukan (seharusnya tidak terjadi jika keranjang terisi dengan menu valid)
                Log::error('Menu dengan ID ' . $menuId . ' tidak ditemukan saat membuat pesanan.');
                continue; // Lanjutkan ke item berikutnya di keranjang jika menu tidak ditemukan
            }

            // Dapatkan seller_id dari menu yang dipesan
            $sellerId = $menu->user_id;

            if (is_null($sellerId)) {
                // Handle jika menu tidak memiliki seller_user_id yang terdefinisi
                Log::warning('Menu ID ' . $menuId . ' tidak memiliki seller_user_id yang terdefinisi. Pesanan tidak dapat ditugaskan ke penjual.');
                // Anda bisa memilih untuk:
                // 1. Melewatkan item ini
                // 2. Mengatur seller_id ke default (misal, admin)
                // 3. Mengembalikan error ke pengguna
                return redirect()->back()->with('error', 'Pesanan gagal: Menu "' . $menu->nama . '" belum memiliki penjual yang terdaftar.');
            }

            try {
                Order::create([
                    'user_id' => $userId,
                    'menu_id' => $menuId,
                    'jumlah' => $item['kuantitas'],
                    'status' => 'Menunggu Konfirmasi',
                    'seller_id' => $sellerId,
                ]);
            } catch (\Exception $e) {
                Log::error('Gagal membuat pesanan untuk menu ID ' . $menuId . ': ' . $e->getMessage());
                return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat pesanan untuk beberapa item. Silakan coba lagi.');
            }
        }

        session()->forget('cart');

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat!');
    }

    public function storeApi(Request $request) // Metode baru untuk API
    {
        $userId = Auth::id();
        $carts = Session()->get('cart', []);

        if (empty($carts)) {
            return redirect()->back()->with('error', 'Keranjang kosong!');
        }

        foreach ($carts as $menuId => $item) {
            $menu = Menu::find($menuId);

            if (!$menu) {
                // Log atau tangani jika menu tidak ditemukan (seharusnya tidak terjadi jika keranjang terisi dengan menu valid)
                Log::error('Menu dengan ID ' . $menuId . ' tidak ditemukan saat membuat pesanan.');
                continue; // Lanjutkan ke item berikutnya di keranjang jika menu tidak ditemukan
            }

            // Dapatkan seller_id dari menu yang dipesan
            $sellerId = $menu->user_id;

            if (is_null($sellerId)) {
                // Handle jika menu tidak memiliki seller_user_id yang terdefinisi
                Log::warning('Menu ID ' . $menuId . ' tidak memiliki seller_user_id yang terdefinisi. Pesanan tidak dapat ditugaskan ke penjual.');
                // Anda bisa memilih untuk:
                // 1. Melewatkan item ini
                // 2. Mengatur seller_id ke default (misal, admin)
                // 3. Mengembalikan error ke pengguna
                return redirect()->back()->with('error', 'Pesanan gagal: Menu "' . $menu->nama . '" belum memiliki penjual yang terdaftar.');
            }

            try {
                Order::create([
                    'user_id' => $userId,
                    'menu_id' => $menuId,
                    'jumlah' => $item['kuantitas'],
                    'status' => 'Menunggu Konfirmasi',
                    'seller_id' => $sellerId,
                ]);
            } catch (\Exception $e) {
                Log::error('Gagal membuat pesanan untuk menu ID ' . $menuId . ': ' . $e->getMessage());
                return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat pesanan untuk beberapa item. Silakan coba lagi.');
            }
        }

        session()->forget('cart');

        return response()->json(['message' => 'Pesanan berhasil dibuat!', 'order' => $order], 201);
    }

    public function index()
    {
        // $orders = Order::with('menu', 'user')->latest()->get();
        $orders = Order::where('seller_id', Auth::id())
                        ->with(['menu', 'user', 'reviews']) // Load relasi menu dan reviews
                        ->latest() // Urutkan dari yang terbaru
                        ->get();
        return view('penjual.orderan', compact('orders'));
    }

    public function selesaikan($id)
    {
        Order::findOrFail($id)->update(['status' => 'Selesai']);
        return back()->with('success', 'Pesanan diselesaikan.');
    }

    public function batal($id)
    {
        Order::findOrFail($id)->update(['status' => 'Dibatalkan']);
        return back()->with('success', 'Pesanan dibatalkan.');
    }
    public function history()
    {
        $userId = auth()->id();
        $orders = Order::with('menu')->where('user_id', $userId)->latest()->get();
        return view('pelanggan.pesanan', compact('orders'));
    }

    public function hapus($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return back()->with('success', 'Pesanan berhasil dihapus!');
    
    }

    public function reorder(Order $order)
    {
        // Pastikan pesanan ini milik user yang sedang login
        if (Auth::id() !== $order->user_id) {
            abort(403, 'Anda tidak diizinkan memesan ulang pesanan ini.');
        }

        // Ambil item dari pesanan lama.
        // Asumsi: Setiap 'Order' item hanya berisi satu 'Menu'.
        // Jika satu order bisa punya banyak item, Anda perlu relasi 'order_items'
        // dan melakukan loop untuk setiap item di dalamnya.
        $menuItem = $order->menu; // Mengakses menu melalui relasi

        if (!$menuItem) {
            return redirect()->back()->with('error', 'Menu tidak ditemukan untuk pesanan ini.');
        }

        // --- Logika Menambahkan ke Keranjang Belanja ---
        // Asumsi: Keranjang belanja disimpan di session sebagai array asosiatif:
        // ['menu_id' => ['nama' => 'Es Coklat', 'harga' => 15000, 'jumlah' => 2]]

        $cart = Session()->get('cart', []); // Ambil keranjang belanja dari session, jika tidak ada, buat array kosong

        $menuId = $menuItem->id;

        if (isset($cart[$menuId])) {
            // Jika menu sudah ada di keranjang, tambahkan jumlahnya
            $cart[$menuId]['kuantitas'] += $order->jumlah;
        } else {
            // Jika menu belum ada, tambahkan sebagai item baru
            $cart[$menuId] = [
                'nama' => $menuItem->nama,
                'harga' => $menuItem->harga,
                'kuantitas' => $order->jumlah, // Tambahkan dengan jumlah dari pesanan lama
                'image_path' => $menuItem->image_path, // Tambahkan path gambar
            ];
        }

        Session()->put('cart', $cart); // Simpan kembali keranjang belanja ke session

        // Redirect ke halaman keranjang belanja atau dashboard dengan pesan sukses
        return redirect()->route('cart.show')->with('success', 'Pesanan "' . $menuItem->nama . '" berhasil ditambahkan kembali ke keranjang!');
        // Anda perlu route dan view 'cart.index' jika belum ada.
    }

}

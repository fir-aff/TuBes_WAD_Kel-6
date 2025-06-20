<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu; // Import model Menu untuk mendapatkan detail produk
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; // Untuk mengelola session keranjang belanja
use Illuminate\Support\Facades\Log; // Untuk debugging

class CartController extends Controller
{
    /**
     * Get the current user's cart content.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $cart = Session::get('cart', []);
        return response()->json($cart);
    }

    /**
     * Add an item to the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'jumlah' => 'nullable|integer|min:1', // Jumlah opsional, default 1
        ]);

        $menu = Menu::find($request->menu_id);
        if (!$menu) {
            return response()->json(['message' => 'Menu not found'], 404);
        }

        $cart = Session::get('cart', []);
        $quantity = $request->jumlah ?? 1; // Default jumlah 1 jika tidak disediakan

        if (isset($cart[$menu->id])) {
            // Jika menu sudah ada di keranjang, tambahkan jumlahnya
            $cart[$menu->id]['jumlah'] += $quantity;
        } else {
            // Jika menu belum ada, tambahkan sebagai item baru
            $cart[$menu->id] = [
                'nama' => $menu->nama,
                'harga' => $menu->harga,
                'jumlah' => $quantity,
                'gambar' => $menu->gambar, // Asumsi nama kolom gambar di tabel menus adalah 'gambar'
            ];
        }

        Session::put('cart', $cart);

        return response()->json(['message' => 'Item added to cart', 'cart' => $cart]);
    }

    /**
     * Update the quantity of an item in the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $menuId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $menuId)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:0', // Jumlah 0 bisa berarti menghapus item
        ]);

        $cart = Session::get('cart', []);

        if (!isset($cart[$menuId])) {
            return response()->json(['message' => 'Item not in cart'], 404);
        }

        if ($request->jumlah == 0) {
            unset($cart[$menuId]); // Hapus item jika jumlahnya 0
        } else {
            $cart[$menuId]['jumlah'] = $request->jumlah;
        }

        Session::put('cart', $cart);

        return response()->json(['message' => 'Cart updated', 'cart' => $cart]);
    }

    /**
     * Remove an item from the cart.
     *
     * @param  int  $menuId
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove($menuId)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$menuId])) {
            unset($cart[$menuId]);
            Session::put('cart', $cart);
            return response()->json(['message' => 'Item removed from cart', 'cart' => $cart]);
        }
        return response()->json(['message' => 'Item not in cart'], 404);
    }

    /**
     * Process the checkout from the cart into an order.
     * This method will interact with the OrderController's store logic.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkout(Request $request)
    {
        // PENTING: Untuk checkout API, Anda perlu memastikan logika pembuatan order
        // di OrderController@store bisa diakses atau dipisahkan ke service.
        // Contoh ini akan memanggil OrderController@store langsung.
        // Pastikan OrderController@store tidak redirect tapi mengembalikan JSON jika ini untuk API.

        try {
            // Panggil metode store dari OrderController.
            // Anda mungkin perlu menyesuaikan OrderController@store
            // agar bisa mengembalikan JSON, bukan redirect, jika dipanggil dari API.
            // Atau, buat Service Layer untuk logika Order dan panggil service tersebut.
            $orderController = app(\App\Http\Controllers\OrderController::class);
            $response = $orderController->store($request);

            // Jika OrderController@store mengembalikan RedirectResponse, ini tidak ideal untuk API.
            // Anda harus mengubah OrderController@store untuk mengembalikan JsonResponse jika dipanggil API.
            // Sebagai alternatif sementara (tidak disarankan untuk produksi):
            if ($response instanceof \Illuminate\Http\RedirectResponse) {
                return response()->json(['message' => 'Checkout initiated, redirecting...'], 200);
            }

            return $response; // Asumsi OrderController@store sudah mengembalikan JsonResponse
        } catch (\Exception $e) {
            Log::error('API Checkout Error: ' . $e->getMessage());
            return response()->json(['message' => 'Checkout failed: ' . $e->getMessage()], 500);
        }
    }
}

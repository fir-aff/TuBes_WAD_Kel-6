<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Cart;


class OrderController extends Controller
{
    public function store(Request $request)
    {
        $userId = Auth::id();
        $carts = session('cart', []);

        if (empty($carts)) {
            return redirect()->back()->with('error', 'Keranjang kosong!');
        }

        foreach ($carts as $menuId => $item) {
            Order::create([
                'user_id' => $userId,
                'menu_id' => $menuId,
                'jumlah' => $item['kuantitas'],
                'status' => 'Menunggu Konfirmasi',
            ]);
        }

        session()->forget('cart');

        return redirect()->route('order.history')->with('success', 'Pesanan berhasil dibuat!');
    }



    public function index()
    {
        $orders = Order::with('menu', 'user')->latest()->get();
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

}

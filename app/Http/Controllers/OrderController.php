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
        $carts = \App\Models\Cart::where('user_id', $userId)->get();

        if ($carts->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang kosong!');
        }

        foreach ($carts as $item) {
            try {
                \Log::info("CART ITEM => menu_id: {$item->menu_id}, jumlah: {$item->jumlah}");

                $order = Order::create([
                    'user_id' => $userId,
                    'menu_id' => $item->menu_id,
                    'jumlah' => $item->jumlah,
                    'status' => 'Menunggu Konfirmasi'
                ]);

                \Log::info("ORDER CREATED ID => " . $order->id);

            } catch (\Exception $e) {
                \Log::error('GAGAL ORDER: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Gagal menyimpan pesanan.');
            }
        }

        \App\Models\Cart::where('user_id', $userId)->delete();

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
}

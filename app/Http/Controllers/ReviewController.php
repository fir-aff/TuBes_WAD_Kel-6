<?php

namespace App\Http\Controllers;

use App\Models\Review; // Import Model Review
use App\Models\Order;  // Import Model Order (untuk relasi jika diperlukan, atau untuk mengambil menu_id)
use App\Models\Menu;   // Import Model Menu (untuk menampilkan detail produk di halaman ulasan)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan user yang sedang login
use Illuminate\Support\Facades\Storage; // Untuk mengelola penyimpanan file
use Illuminate\Support\Facades\Log; // Untuk debugging jika diperlukan

class ReviewController extends Controller
{
    //
    public function store(Request $request) {
        // dd($request->all()); // Untuk debugging
        // 1. Validasi Input
        // Pastikan order_id valid, user_id valid, rating antara 1-5, comment string, dan image adalah file gambar
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'user_id' => 'required|exists:users,id',
            'menu_id' => 'required|exists:menus,id', // Harus ada hidden input ini di form modal
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Opsional, gambar, max 2MB
        ]);

        $imagePath = null;
        // 2. Handle Upload Gambar (Jika Ada)
        if ($request->hasFile('image')) {
            try {
                $image = $request->file('image');
                // Buat nama file unik menggunakan timestamp dan nama asli file
                $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                // Simpan gambar ke folder 'reviews' di disk 'public' (storage/app/public/reviews)
                $imagePath = $image->storeAs('reviews', $fileName, 'public');
            } catch (\Exception $e) {
                Log::error('Gagal upload gambar ulasan: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunggah gambar.');
            }
        }

        // 3. Simpan Ulasan ke Database
        try {
            Review::create([
                'order_id' => $validatedData['order_id'],
                'user_id' => $validatedData['user_id'],
                'menu_id' => $validatedData['menu_id'],
                'rating' => $validatedData['rating'],
                'comment' => $validatedData['comment'],
                'image_path' => $imagePath, // Simpan path gambar
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan ulasan ke database: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan ulasan.');
        }


        // 4. Redirect atau Respon
        return redirect()->back()->with('success', 'Ulasan berhasil ditambahkan!');
        // Jika ini adalah API, Anda mungkin ingin mengembalikan JSON:
        // return response()->json(['message' => 'Review created successfully', 'review' => $review], 201);
    }

    /**
     * Display the reviews for a specific product (menu).
     *
     * @param  \App\Models\Menu  $menu Menggunakan Route Model Binding
     * @return \Illuminate\View\View
     */
    public function showProductReviews(Menu $menu)
    {
        $menu->load('reviews.user');
        // Pastikan relasi 'reviews' ada di model Menu dan 'user' di model Review
        $userId = Auth::id();

        // Ambil semua ulasan untuk menu ini, urutkan berdasarkan created_at descending
        $allReviews = Review::where('menu_id', $menu->id)
                            ->with('user') // Eager load user yang memberi ulasan
                            ->orderByDesc('created_at') // Urutkan ulasan terbaru
                            ->get();

        // Pisahkan ulasan milik user yang sedang login
        $userReview = $allReviews->firstWhere('user_id', $userId);

        // Ambil ulasan lain, kecuali ulasan user yang sedang login
        $otherReviews = $allReviews->filter(function ($review) use ($userId) {
            return $review->user_id !== $userId;
        });

        // Kirim data ke view
        return view('reviews.show_product', compact('menu', 'userReview', 'otherReviews'));
    }

    /**
     * Show the form for editing the specified review.
     *
     * @param  \App\Models\Review  $review Menggunakan Route Model Binding
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function edit(Review $review)
    {
        // Pastikan hanya user pemilik ulasan yang bisa mengedit
        if (Auth::id() !== $review->user_id) {
            // Jika tidak diizinkan, kembalikan response 403 Forbidden
            abort(403, 'Anda tidak diizinkan mengedit ulasan ini.');
        }

        // Load relasi 'menu' dari review jika ingin menampilkan nama menu di form edit
        $review->load('menu');

        // Mengarahkan ke view edit_history.blade.php
        return view('reviews.edit_history', compact('review'));
    }

    /**
     * Update the specified review in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review Menggunakan Route Model Binding
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Review $review)
    {
        // Pastikan hanya user pemilik ulasan yang bisa mengedit
        if (Auth::id() !== $review->user_id) {
            abort(403, 'Anda tidak diizinkan memperbarui ulasan ini.');
        }

        // 1. Validasi Input
        $validatedData = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Opsional
            'clear_image' => 'nullable|boolean', // Checkbox untuk menghapus gambar
        ]);

        $imagePath = $review->image_path; // Default: pertahankan gambar lama

        // 2. Handle Update Gambar
        if ($request->hasFile('image')) {
            try {
                // Hapus gambar lama jika ada
                if ($review->image_path && Storage::disk('public')->exists($review->image_path)) {
                    Storage::disk('public')->delete($review->image_path);
                }
                $image = $request->file('image');
                $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('reviews', $fileName, 'public');
            } catch (\Exception $e) {
                Log::error('Gagal upload gambar saat update ulasan: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunggah gambar baru.');
            }
        } elseif ($request->boolean('clear_image')) { // Jika checkbox 'Hapus gambar ini' dicentang
            if ($review->image_path && Storage::disk('public')->exists($review->image_path)) {
                Storage::disk('public')->delete($review->image_path);
                $imagePath = null; // Set path gambar menjadi null di database
            }
        }

        // 3. Perbarui Ulasan di Database
        try {
            $review->update([
                'rating' => $validatedData['rating'],
                'comment' => $validatedData['comment'],
                'image_path' => $imagePath, // Simpan path gambar yang baru atau null
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui ulasan di database: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui ulasan.');
        }


        // 4. Redirect
        // return redirect()->route('reviews.show_product', $review->menu->id)->with('success', 'Ulasan berhasil diperbarui!');
        return redirect()->route('reviews.show_product', $review->menu_id)->with('success', 'Ulasan berhasil diperbarui!');
    }

    /**
     * Remove the specified review from storage.
     *
     * @param  \App\Models\Review  $review Menggunakan Route Model Binding
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Review $review)
    {
        // Pastikan hanya user pemilik ulasan yang bisa menghapus
        if (Auth::id() !== $review->user_id) {
            abort(403, 'Anda tidak diizinkan menghapus ulasan ini.');
        }

        try {
            // Hapus gambar terkait jika ada
            if ($review->image_path && Storage::disk('public')->exists($review->image_path)) {
                Storage::disk('public')->delete($review->image_path);
            }

            // Hapus ulasan dari database
            $menuIdForRedirect = $review->menu_id;
            $review->delete();
        } catch (\Exception $e) {
            Log::error('Gagal menghapus ulasan atau gambar: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus ulasan.');
        }


        return redirect()->route('reviews.show_product', $menuIdForRedirect)->with('success', 'Ulasan berhasil dihapus!');
    }
}

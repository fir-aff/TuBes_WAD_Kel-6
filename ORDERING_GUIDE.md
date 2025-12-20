# 📋 Panduan Sistem Pemesanan (Ordering System)

## 🎯 Alur Pemesanan Lengkap

### 1️⃣ **Halaman Utama (Welcome/Homepage)**
- **Route:** `/` (`welcome`)
- **Controller:** `WelcomeController@index`
- **Fitur:**
  - Lihat semua menu tersedia
  - Filter berdasarkan kategori (Makanan Berat, Minuman, Camilan, Dessert)
  - Cari menu dengan keyword (nama atau deskripsi) via search box
  - Tombol "Tambah ke Keranjang" untuk setiap menu

### 2️⃣ **Menambah Item ke Keranjang**
- **Route:** `POST /keranjang/tambah` (`cart.add`)
- **Controller:** `CartController@add`
- **Data yang dikirim:**
  ```html
  <form action="{{ route('cart.add') }}" method="POST">
      <input name="menu_id" value="..." required>      <!-- ID menu -->
      <input name="nama" value="..." required>         <!-- Nama menu -->
      <input name="harga" value="..." required>        <!-- Harga menu -->
      <input name="gambar" value="...">                <!-- Gambar (opsional) -->
      <button type="submit">+ Tambah ke Keranjang</button>
  </form>
  ```
- **Proses:**
  - Validasi data (menu_id harus ada di database)
  - Jika menu sudah ada di cart → tambah kuantitas +1
  - Jika menu baru → tambah ke cart dengan kuantitas 1
  - Simpan ke session `session('cart')`
  - Redirect dengan pesan "Menu berhasil ditambahkan ke keranjang!"

### 3️⃣ **Lihat Keranjang Belanja**
- **Route:** `GET /keranjang/` (`cart.show`)
- **Controller:** `CartController@show`
- **Tampilan:**
  - List semua item di keranjang dengan:
    - Gambar menu
    - Nama menu
    - Harga satuan
    - Kuantitas (dengan field untuk update)
    - Subtotal (harga × kuantitas)
    - Tombol hapus item
  - **Ringkasan Pesanan:**
    - Total pembayaran (jumlah dari semua subtotal)
    - Tombol "Lanjutkan Pembayaran"

### 4️⃣ **Update Kuantitas Item**
- **Route:** `POST /keranjang/update` (`cart.update`)
- **Controller:** `CartController@update`
- **Data yang dikirim:**
  ```html
  <form action="{{ route('cart.update') }}" method="POST">
      <input name="menu_id" value="...">
      <input name="kuantitas" value="5">  <!-- Kuantitas baru -->
      <button type="submit">Update</button>
  </form>
  ```
- **Proses:**
  - Update kuantitas item di cart
  - Redirect ke halaman cart dengan pesan sukses

### 5️⃣ **Hapus Item dari Keranjang**
- **Route:** `POST /keranjang/hapus` (`cart.remove`)
- **Controller:** `CartController@remove`
- **Data yang dikirim:**
  ```html
  <form action="{{ route('cart.remove') }}" method="POST">
      <input name="menu_id" value="...">
      <button type="submit">🗑️ Hapus</button>
  </form>
  ```
- **Proses:**
  - Hapus item dari cart session
  - Redirect dengan pesan "Item berhasil dihapus"

### 6️⃣ **Checkout / Finalisasi Pesanan**
- **Route:** `POST /order/selesai` (`order.done`)
- **Controller:** `OrderController@store`
- **UI:** Modal pembayaran di keranjang
  1. User klik "Lanjutkan Pembayaran"
  2. Modal terbuka: instruksi scan QR code dan total pembayaran
  3. User setelah membayar, klik "Selesai Bayar"
  4. Form POST ke `order.done` dengan CSRF token

- **Proses di Backend:**
  - Validasi: user harus sudah login (middleware `auth`)
  - Ambil user_id dari Auth
  - Ambil cart items dari session
  - Validasi: keranjang tidak boleh kosong
  - **Loop untuk setiap item di keranjang:**
    ```php
    foreach ($carts as $menuId => $item) {
        // 1. Cari menu di database
        $menu = Menu::find($menuId);
        
        // 2. Validasi menu ada & memiliki seller
        if (!$menu) {
            Log error dan skip item
        }
        
        $sellerId = $menu->user_id;  // Penjual yang membuat menu ini
        if (!$sellerId) {
            Return error: "Menu belum punya penjual"
        }
        
        // 3. Buat Order record di database
        Order::create([
            'user_id'  => $userId,      // Pembeli
            'menu_id'  => $menuId,
            'jumlah'   => $item['kuantitas'],
            'status'   => 'Menunggu Konfirmasi',
            'seller_id' => $sellerId    // Penjual
        ]);
    }
    ```
  - Hapus cart dari session: `session()->forget('cart')`
  - Redirect ke `/orders` dengan pesan "Pesanan berhasil dibuat!"

---

## 📊 Database Schema (Order Table)

```sql
CREATE TABLE orders (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,          -- Pembeli
    menu_id BIGINT NOT NULL,          -- Menu yang dipesan
    jumlah INT NOT NULL,              -- Jumlah item
    status VARCHAR(50) DEFAULT 'Menunggu Konfirmasi',  
              -- Status: Menunggu Konfirmasi | Selesai | Dibatalkan
    seller_id BIGINT NOT NULL,        -- Penjual (dari menu->user_id)
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (menu_id) REFERENCES menus(id),
    FOREIGN KEY (seller_id) REFERENCES users(id)
);
```

---

## 📜 Riwayat Pesanan (Order History)

- **Route:** `GET /pelanggan/pesanan` (`order.history`)
- **Controller:** `OrderController@history`
- **Akses:** Hanya user dengan role `pelanggan` (via middleware `role:pelanggan`)
- **Tampilan:**
  - Daftar pesanan user yang login
  - Untuk setiap order:
    - Gambar menu
    - Nama menu
    - Jumlah item
    - Status pesanan (warna-coded):
      - 🟡 Menunggu Konfirmasi (orange)
      - 🟢 Selesai (green)
      - 🔴 Dibatalkan (red)
    - **Aksi (jika status "Selesai"):**
      - ⭐ Beri Ulasan (klik → modal review)
      - 🔄 Beli Lagi (re-order)

---

## ⭐ Sistem Ulasan (Reviews)

Setelah order selesai, pelanggan bisa memberikan ulasan:

- **Route:** `POST /reviews` (`reviews.store`)
- **Controller:** `ReviewController@store`
- **Data di modal:**
  - ⭐ Rating (1-5 bintang, interactive)
  - 💬 Comment/ulasan text
  - 📷 Foto (optional)
- **Hasil:**
  - Ulasan tersimpan di tabel `reviews`
  - Linked ke: `user_id` (pembuat ulasan), `menu_id`, `order_id`

---

## 🛍️ Untuk Penjual: Melihat Pesanan Masuk

- **Route:** `GET /penjual/orderan` (`orderan.index`)
- **Controller:** `OrderController@index`
- **Akses:** Hanya user dengan role `penjual` (via middleware `role:penjual`)
- **Tampilan:**
  - List pesanan dari menu milik penjual ini
  - Untuk setiap order:
    - Nama pembeli
    - Menu yang dipesan
    - Jumlah
    - Status
    - **Aksi:**
      - ✓ Selesaikan: ubah status → "Selesai"
      - ✗ Batalkan: ubah status → "Dibatalkan"
      - 🗑️ Hapus order dari sistem

---

## 👤 Mengubah Role User (Admin Only)

### Cara 1: Via Admin Panel
- **Route:** `GET /admin/users` 
- **UI:** Tabel user dengan tombol "Promote"
- **Proses:**
  - Klik tombol promote di user row
  - Pilih role: Admin / Penjual / Pelanggan
  - Konfirmasi → role user berubah

### Cara 2: Manual via Terminal/API
```bash
# Login as admin
# POST /admin/users/{user_id}/promote dengan role di body
```

---

## 🔄 Alur Lengkap (Step-by-Step)

### **Skenario: User Memesan Makanan**

1. **User buka halaman utama** → `/`
2. **User melihat menu** → `GET /` (WelcomeController@index)
3. **User cari/filter menu** → search by `?q=nasi` atau `?kategori=Makanan%20Berat`
4. **User klik "Tambah ke Keranjang"** → `POST /keranjang/tambah`
   - Item disimpan di session
5. **User buka Keranjang** → `GET /keranjang/` (CartController@show)
   - Lihat total pembayaran
6. **User update kuantitas (opsional)** → `POST /keranjang/update`
7. **User klik "Lanjutkan Pembayaran"** → Modal pembayaran terbuka
8. **User scan QR code & transfer uang**
9. **User klik "Selesai Bayar"** → `POST /order/selesai` (OrderController@store)
   - Backend: loop setiap item → buat Order record
   - Cart session dihapus
   - Redirect ke order list
10. **Order muncul di riwayat pesanan** → `GET /pelanggan/pesanan`
11. **Penjual melihat pesanan masuk** → `GET /penjual/orderan`
    - Bisa selesaikan/batalkan
12. **Jika order selesai, pelanggan bisa beri ulasan** → `POST /reviews`

---

## ✅ Checklist Setup

- [x] CartController methods (add, update, remove, show)
- [x] OrderController methods (store untuk checkout)
- [x] UI keranjang dengan modal pembayaran
- [x] UI riwayat pesanan dengan ulasan
- [x] Routes untuk cart dan order
- [x] Middleware untuk auth & role-based access
- [x] Database Order model dengan relasi user & menu
- [ ] Database seeded dengan menu dari penjual
- [ ] Jalankan: `npm install && npm run dev` untuk Vite assets

---

## 🚀 Testing Pemesanan

1. **Login sebagai Pelanggan**
   - Username: sesuai data seeded
   - Role harus: `pelanggan`

2. **Tambah item ke cart dari homepage**

3. **Buka keranjang & lakukan checkout**

4. **Cek order muncul di riwayat pesanan**

5. **Login sebagai Penjual (role: `penjual`)**
   - Menu owner: penjual tersebut
   - Buka `/penjual/orderan`
   - Lihat pesanan dari customer

6. **Penjual selesaikan pesanan → status "Selesai"**

7. **Pelanggan lihat di riwayat → beri ulasan**

---

## 📝 File-File Terkait

- Controllers: [CartController](app/Http/Controllers/CartController.php), [OrderController](app/Http/Controllers/OrderController.php)
- Views: [keranjang.blade.php](resources/views/pelanggan/keranjang.blade.php), [pesanan.blade.php](resources/views/pelanggan/pesanan.blade.php)
- Routes: [web.php](routes/web.php)
- Models: [Order.php](app/Models/Order.php), [Menu.php](app/Models/Menu.php), [User.php](app/Models/User.php)


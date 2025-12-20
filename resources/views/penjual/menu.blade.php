<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Menu - Kantin Kampus</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-b from-slate-50 to-slate-100 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-2">
                    <span class="text-2xl">🍽️</span>
                    <span class="text-xl font-bold text-orange-600">Manajemen Menu</span>
                </div>
                <a href="/penjual/manajemen" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg font-semibold text-slate-700 transition">
                    ← Kembali
                </a>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session('success'))
            <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-lg text-green-700 font-medium">
                ✓ {{ session('success') }}
            </div>
        @endif

        <!-- Form Tambah Menu -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-orange-500 to-red-500 p-6 text-white">
                <h2 class="text-2xl font-bold">➕ Tambah Menu Baru</h2>
            </div>
            
            <form method="POST" action="{{ url('/penjual/menu') }}" enctype="multipart/form-data" class="p-8">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                    <!-- Nama Menu -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Menu</label>
                        <input type="text" name="nama" 
                               class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition bg-slate-50"
                               placeholder="Contoh: Nasi Goreng"
                               required>
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori</label>
                        <select name="kategori" 
                                class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition bg-slate-50"
                                required>
                            <option value="">Pilih Kategori</option>
                            <option value="Minuman">🥤 Minuman</option>
                            <option value="Makanan Berat">🍖 Makanan Berat</option>
                            <option value="Camilan">🍟 Camilan</option>
                            <option value="Dessert">🍰 Dessert</option>
                        </select>
                    </div>

                    <!-- Harga -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Harga (Rp)</label>
                        <input type="number" name="harga" 
                               class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition bg-slate-50"
                               placeholder="Contoh: 15000"
                               required>
                    </div>

                    <!-- Gambar -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Gambar</label>
                        <input type="file" name="gambar" 
                               class="block w-full px-4 py-3 border-2 border-dashed border-slate-200 rounded-lg focus:outline-none focus:border-orange-500 transition bg-slate-50"
                               accept="image/*">
                    </div>
                </div>

                <!-- Deskripsi (Full Width) -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" 
                              class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition bg-slate-50"
                              placeholder="Deskripsikan menu Anda..."
                              rows="3"></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold rounded-lg hover:shadow-lg transition">
                    💾 Simpan Menu
                </button>
            </form>
        </div>

        <!-- Daftar Menu -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 p-6 text-white">
                <h2 class="text-2xl font-bold">📋 Daftar Menu Anda</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-100 border-b-2 border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold text-slate-700">Menu</th>
                            <th class="px-6 py-4 text-left font-semibold text-slate-700">Kategori</th>
                            <th class="px-6 py-4 text-left font-semibold text-slate-700">Harga</th>
                            <th class="px-6 py-4 text-center font-semibold text-slate-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($menus as $menu)
                            <tr class="border-b border-slate-200 hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($menu->gambar)
                                            <img src="{{ asset('storage/menu/' . $menu->gambar) }}" alt="{{ $menu->nama }}" class="w-12 h-12 rounded-lg object-cover">
                                        @else
                                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-orange-100 to-red-100 flex items-center justify-center text-xl">🍽️</div>
                                        @endif
                                        <span class="font-semibold text-slate-800">{{ $menu->nama }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold 
                                        @if($menu->kategori == 'Minuman') bg-blue-100 text-blue-700
                                        @elseif($menu->kategori == 'Makanan Berat') bg-red-100 text-red-700
                                        @elseif($menu->kategori == 'Camilan') bg-yellow-100 text-yellow-700
                                        @else bg-pink-100 text-pink-700 @endif">
                                        {{ $menu->kategori }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-bold text-orange-600">
                                    Rp {{ number_format($menu->harga, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center space-x-2">
                                    <button type="button" 
                                            class="inline-block px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-semibold transition"
                                            data-bs-toggle="modal" data-bs-target="#editModal{{ $menu->id }}">
                                        ✏️ Edit
                                    </button>

                                    <form method="POST" action="{{ url('/penjual/menu/' . $menu->id) }}" class="inline-block"
                                          onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-semibold transition">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    @foreach ($menus as $menu)
        <div class="modal fade" id="editModal{{ $menu->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-2xl">
                    <form method="POST" action="{{ url('/penjual/menu/' . $menu->id) }}" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="modal-header bg-gradient-to-r from-blue-500 to-cyan-500 text-white border-0">
                            <h5 class="modal-title font-bold">✏️ Edit Menu</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-8 space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nama</label>
                                <input type="text" name="nama" 
                                       class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 focus:outline-none focus:border-blue-500 transition bg-slate-50"
                                       value="{{ $menu->nama }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori</label>
                                <select name="kategori" class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 focus:outline-none focus:border-blue-500 transition bg-slate-50" required>
                                    <option value="Minuman" @selected($menu->kategori == 'Minuman')>🥤 Minuman</option>
                                    <option value="Makanan Berat" @selected($menu->kategori == 'Makanan Berat')>🍖 Makanan Berat</option>
                                    <option value="Camilan" @selected($menu->kategori == 'Camilan')>🍟 Camilan</option>
                                    <option value="Dessert" @selected($menu->kategori == 'Dessert')>🍰 Dessert</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Harga (Rp)</label>
                                <input type="number" name="harga" 
                                       class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 focus:outline-none focus:border-blue-500 transition bg-slate-50"
                                       value="{{ $menu->harga }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi</label>
                                <textarea name="deskripsi" 
                                          class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 focus:outline-none focus:border-blue-500 transition bg-slate-50"
                                          rows="3">{{ $menu->deskripsi }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Gambar Baru</label>
                                <input type="file" name="gambar" 
                                       class="block w-full px-4 py-3 border-2 border-dashed border-slate-200 rounded-lg focus:outline-none focus:border-blue-500 transition bg-slate-50"
                                       accept="image/*">
                                @if ($menu->gambar)
                                    <img src="{{ asset('storage/menu/' . $menu->gambar) }}" alt="{{ $menu->nama }}" class="w-24 h-24 rounded-lg object-cover mt-3">
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer border-t p-4 gap-3">
                            <button type="button" class="px-6 py-2 bg-slate-200 text-slate-800 rounded-lg font-semibold hover:bg-slate-300 transition" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-lg font-semibold hover:shadow-lg transition">
                                💾 Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @vite('resources/js/app.js')
</body>
</html>

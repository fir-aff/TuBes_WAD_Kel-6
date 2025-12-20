<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna - Admin</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-b from-slate-50 to-slate-100 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-2">
                    <span class="text-2xl">🧑‍💼</span>
                    <span class="text-xl font-bold text-orange-600">Admin Panel</span>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-semibold transition">
                        🚪 Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Title -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-slate-800 mb-2">Kelola Pengguna</h1>
            <p class="text-slate-600">Kelola role dan data pengguna aplikasi Kantin Kampus</p>
        </div>

        <!-- Success Alert -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-lg text-green-700 font-medium">
                ✓ {{ session('success') }}
            </div>
        @endif

        <!-- Users Table Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-500 p-6 text-white">
                <h2 class="text-2xl font-bold">👥 Daftar Pengguna ({{ $users->count() }})</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-100 border-b-2 border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold text-slate-700">Nama</th>
                            <th class="px-6 py-4 text-left font-semibold text-slate-700">Email</th>
                            <th class="px-6 py-4 text-left font-semibold text-slate-700">Role</th>
                            <th class="px-6 py-4 text-center font-semibold text-slate-700">Ubah Role</th>
                            <th class="px-6 py-4 text-center font-semibold text-slate-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="border-b border-slate-200 hover:bg-slate-50 transition">
                                <!-- Nama -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-400 to-red-400 flex items-center justify-center text-white font-bold">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <span class="font-semibold text-slate-800">{{ $user->name }}</span>
                                    </div>
                                </td>

                                <!-- Email -->
                                <td class="px-6 py-4 text-slate-600">
                                    {{ $user->email }}
                                </td>

                                <!-- Current Role -->
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                        @if($user->role == 'admin') bg-red-100 text-red-700
                                        @elseif($user->role == 'penjual') bg-blue-100 text-blue-700
                                        @else bg-green-100 text-green-700 @endif">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>

                                <!-- Change Role -->
                                <td class="px-6 py-4 text-center">
                                    @if (auth()->id() !== $user->id)
                                        <form action="{{ url('/admin/users/' . $user->id . '/promote') }}" method="POST" class="flex justify-center gap-2 items-center">
                                            @csrf
                                            <select name="role" class="px-3 py-2 rounded-lg border-2 border-slate-200 focus:outline-none focus:border-indigo-500 transition bg-slate-50 font-semibold text-sm">
                                                <option value="pelanggan" {{ $user->role == 'pelanggan' ? 'selected' : '' }}>👤 Pelanggan</option>
                                                <option value="penjual" {{ $user->role == 'penjual' ? 'selected' : '' }}>🍴 Penjual</option>
                                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>🧑‍💼 Admin</option>
                                            </select>
                                            <button type="submit" class="px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg font-semibold transition text-sm">
                                                ✓ Ubah
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-500 italic">Tidak bisa ubah role sendiri</span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 text-center">
                                    @if (auth()->id() !== $user->id)
                                        <div class="flex justify-center gap-2">
                                            <!-- Reset Password Button -->
                                            <button type="button" 
                                                    onclick="openResetModal({{ $user->id }}, '{{ $user->name }}')"
                                                    class="px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-semibold transition text-sm">
                                                🔑 Reset
                                            </button>
                                            
                                            <!-- Delete Button -->
                                            <form action="{{ url('/admin/users/' . $user->id) }}" method="POST" class="inline-block"
                                                  onsubmit="return confirm('Yakin ingin menghapus user ini? Tindakan ini tidak bisa dibatalkan!')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-semibold transition text-sm">
                                                    🗑️ Hapus
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-500">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Stats Card (optional) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-8">
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-sm font-semibold">Total Pengguna</p>
                        <p class="text-3xl font-bold text-slate-800 mt-2">{{ $users->count() }}</p>
                    </div>
                    <span class="text-4xl">👥</span>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-sm font-semibold">Pelanggan</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">{{ $users->where('role', 'pelanggan')->count() }}</p>
                    </div>
                    <span class="text-4xl">👤</span>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-sm font-semibold">Penjual</p>
                        <p class="text-3xl font-bold text-blue-600 mt-2">{{ $users->where('role', 'penjual')->count() }}</p>
                    </div>
                    <span class="text-4xl">🍴</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div id="resetModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-amber-500 to-orange-500 text-white p-6">
                <h2 class="text-2xl font-bold">🔑 Reset Password</h2>
                <p class="text-amber-50 text-sm mt-1" id="resetUserName"></p>
            </div>

            <!-- Modal Body -->
            <form id="resetForm" method="POST" class="p-8 space-y-6">
                @csrf
                <input type="hidden" name="user_id" id="resetUserId">

                <div class="bg-amber-50 rounded-lg p-4 border border-amber-200">
                    <p class="text-amber-800 text-sm font-medium">
                        ⚠️ Ini akan menghasilkan password temporary. Berikan password baru ke user untuk login ulang.
                    </p>
                </div>

                <!-- New Password Input -->
                <div>
                    <label for="newPassword" class="block text-sm font-semibold text-slate-700 mb-2">
                        Password Baru (Generate Otomatis)
                    </label>
                    <div class="flex gap-2">
                        <input type="text" 
                               id="newPassword" 
                               name="new_password"
                               readonly
                               class="flex-1 px-4 py-3 rounded-lg border-2 border-slate-200 bg-slate-50 font-mono text-sm"
                               placeholder="Password akan generate otomatis">
                        <button type="button" 
                                onclick="generatePassword()"
                                class="px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-semibold transition">
                                🔄 Generate
                        </button>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" 
                            onclick="closeResetModal()"
                            class="flex-1 px-4 py-3 bg-slate-200 text-slate-800 rounded-lg font-semibold hover:bg-slate-300 transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-lg font-semibold hover:shadow-lg transition">
                        ✓ Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function generatePassword() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%';
            let password = '';
            for (let i = 0; i < 12; i++) {
                password += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('newPassword').value = password;
        }

        function openResetModal(userId, userName) {
            document.getElementById('resetUserId').value = userId;
            document.getElementById('resetUserName').innerText = 'Reset password untuk: ' + userName;
            document.getElementById('newPassword').value = '';
            document.getElementById('resetModal').classList.remove('hidden');
            generatePassword();
        }

        function closeResetModal() {
            document.getElementById('resetModal').classList.add('hidden');
        }

        // Handle form submission
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const userId = document.getElementById('resetUserId').value;
            const newPassword = document.getElementById('newPassword').value;
            
            if (!newPassword) {
                alert('Generate password terlebih dahulu!');
                return;
            }

            fetch('/admin/users/' + userId + '/reset-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    new_password: newPassword
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✓ Password user berhasil direset ke: ' + newPassword + '\n\nBerikan password ini kepada user untuk login ulang.');
                    closeResetModal();
                    location.reload();
                } else {
                    alert('✗ ' + (data.message || 'Gagal reset password'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat reset password');
            });
        });

        // Close modal when clicking outside
        document.getElementById('resetModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeResetModal();
            }
        });
    </script>

    @vite('resources/js/app.js')

</body>
</html>

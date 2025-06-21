<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Menambahkan kolom seller_id sebagai foreign key
            // Asumsi ada tabel 'sellers' atau 'users' yang menyimpan data penjual
            // Jika penjual adalah user biasa, maka foreign key bisa merujuk ke tabel 'users'
            // Jika ada tabel 'sellers' terpisah, ganti 'users' menjadi 'sellers'
            $table->foreignId('seller_id')->nullable()->constrained('users')->onDelete('set null'); // Atau onDelete('cascade')
            // nullable() karena pesanan lama mungkin tidak memiliki seller_id
            // constrained('users') berarti merujuk ke tabel 'users'
            // onDelete('set null') berarti jika user penjual dihapus, seller_id di order akan menjadi null
            // Atau Anda bisa pakai onDelete('cascade') jika ingin order ikut terhapus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Menghapus foreign key constraint terlebih dahulu sebelum menghapus kolom
            $table->dropForeign(['seller_id']);
            $table->dropColumn('seller_id');
        });
    }
};

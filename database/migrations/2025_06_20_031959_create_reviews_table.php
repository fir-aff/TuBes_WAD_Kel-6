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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id(); // Auto-incrementing Primary Key
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Foreign Key ke tabel orders
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign Key ke tabel users (asumsi ada)
            $table->integer('rating'); // Kolom untuk rating (misal 1-5)
            $table->text('comment')->nullable(); // Kolom untuk komentar (bisa kosong)
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
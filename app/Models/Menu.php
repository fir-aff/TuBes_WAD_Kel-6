<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'kategori',
        'harga',
        'deskripsi',
        'gambar',
    ];

    public function reviews()
    {
        // Asumsi: Ada kolom 'menu_id' di tabel 'reviews'
        return $this->hasMany(Review::class, 'menu_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

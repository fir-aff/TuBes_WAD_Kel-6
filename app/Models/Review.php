<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    // Definisikan kolom-kolom yang bisa diisi secara massal (mass assignable)
    protected $fillable = [
        'order_id',
        'user_id',
        'menu_id',
        'rating',
        'comment',
        'image_path',
    ];

    // Definisikan relasi dengan Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Definisikan relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function menu() // <<< PASTIKAN RELASI INI ADA
    {
        return $this->belongsTo(Menu::class);
    }
}
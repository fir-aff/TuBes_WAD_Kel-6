<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'menu_id',
        'jumlah',
        'status',
        'seller_id',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id'); // Merujuk ke User model dengan foreign key 'seller_id'
    }
}

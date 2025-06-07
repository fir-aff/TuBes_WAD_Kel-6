<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu; // Import model Menu

class WelcomeController extends Controller
{
    /**
     * Menampilkan halaman utama dengan semua menu.
     */
    public function index()
    {
        $menus = Menu::all();
        return view('welcome', compact('menus'));
    }
}
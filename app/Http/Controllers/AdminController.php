<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function showUsers()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function promote(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $roleBaru = $request->role;
        $user->role = $roleBaru;
        $user->save();

    $pesan = 'User berhasil diubah menjadi ' . $roleBaru . '.';

    return redirect()->back()->with('success', $pesan);
    }
}

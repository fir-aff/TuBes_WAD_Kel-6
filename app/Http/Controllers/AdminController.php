<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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

    public function destroy($id)
    {
        if (auth()->id() == $id) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'new_password' => 'required|string|min:8',
        ]);

        if (auth()->id() == $id) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa reset password akun sendiri.'
            ]);
        }

        $user = User::findOrFail($id);
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password user berhasil direset.'
        ]);
    }
}


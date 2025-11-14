<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemilik;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Exception;

class PemilikController extends Controller
{
    public function index()
    {
        $pemiliks = Pemilik::select(
            'pemilik.*',
            'user.nama',
            'user.email'
        )
            ->join('user', 'user.iduser', '=', 'pemilik.iduser')
            ->get();
        return view('admin.pemilik.index', compact('pemiliks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_user' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'no_wa' => 'required|string|max:20',
            'alamat' => 'nullable|string|max:255',
        ]);

        try {
            $user = User::create([
                'nama' => $request->nama_user,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->roles()->attach(5); // 5 is the role id for 'Pemilik'

            Pemilik::create([
                'iduser' => $user->iduser,
                'no_wa' => $request->no_wa,
                'alamat' => $request->alamat,
            ]);

            return redirect()->back()->with('success', 'Pemilik berhasil ditambahkan.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan pemilik: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $pemilik = Pemilik::findOrFail($id);
        $user = $pemilik->user;

        $request->validate([
            'nama_user' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->iduser . ',iduser',
            'no_wa' => 'required|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'new_password' => 'nullable|string|min:6',
        ]);

        try {
            $user->update([
                'nama' => $request->nama_user,
                'email' => $request->email,
            ]);

            if ($request->filled('new_password')) {
                $user->update([
                    'password' => Hash::make($request->new_password),
                ]);
            }

            $pemilik->update([
                'no_wa' => $request->no_wa,
                'alamat' => $request->alamat,
            ]);

            return redirect()->back()->with('success', 'Pemilik berhasil diperbarui.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui pemilik: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $pemilik = Pemilik::findOrFail($id);

            // Check if pemilik has any pets
            if ($pemilik->pets()->exists()) {
                return redirect()->back()->with('error', 'Gagal menghapus pemilik. Pemilik ini memiliki data pet.');
            }

            $pemilik->user->delete();
            $pemilik->delete();

            return redirect()->back()->with('success', 'Pemilik berhasil dihapus.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus pemilik: ' . $e->getMessage());
        }
    }

    public function getPemilikData($id)
    {
        try {
            $pemilik = Pemilik::with('user')->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'idpemilik' => $pemilik->idpemilik,
                    'nama_user' => $pemilik->user->nama,
                    'email' => $pemilik->user->email,
                    'no_wa' => $pemilik->no_wa,
                    'alamat' => $pemilik->alamat
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pemilik tidak ditemukan'
            ], 404);
        }
    }
}

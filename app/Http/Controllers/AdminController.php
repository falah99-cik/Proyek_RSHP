<?php

namespace App\Http\Controllers;

use App\Models\Kategori; // Assuming you have a Kategori model
use App\Models\KategoriKlinis; // Assuming you have a KategoriKlinis model
use App\Models\KodeTindakanTerapi; // Assuming you have a KodeTindakanTerapi model
use App\Models\Pemilik;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:kategori,nama_kategori'
        ]);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:kategori,nama_kategori,' . $id . ',idkategori'
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update([
            'nama_kategori' => $request->nama_kategori
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            $kategori = Kategori::findOrFail($id);
            $kategori->delete();
            return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1451) { // Foreign key constraint violation
                return redirect()->back()->with('error', 'Gagal menghapus kategori. Kategori ini sedang digunakan.');
            } else {
                return redirect()->back()->with('error', 'Gagal menghapus kategori. Terjadi kesalahan database.');
            }
        }
    }

    public function storeKategoriKlinis(Request $request)
    {
        $request->validate([
            'nama_kategori_klinis' => 'required|unique:kategori_klinis,nama_kategori_klinis'
        ]);

        KategoriKlinis::create([
            'nama_kategori_klinis' => $request->nama_kategori_klinis
        ]);

        return redirect()->back()->with('success', 'Kategori klinis berhasil ditambahkan.');
    }

    public function updateKategoriKlinis(Request $request, $id)
    {
        $request->validate([
            'nama_kategori_klinis' => 'required|unique:kategori_klinis,nama_kategori_klinis,' . $id . ',idkategori_klinis'
        ]);

        $kategori = KategoriKlinis::findOrFail($id);
        $kategori->update([
            'nama_kategori_klinis' => $request->nama_kategori_klinis
        ]);

        return redirect()->back()->with('success', 'Kategori klinis berhasil diperbarui.');
    }

    public function destroyKategoriKlinis($id)
    {
        try {
            $kategori = KategoriKlinis::findOrFail($id);
            $kategori->delete();
            return redirect()->back()->with('success', 'Kategori klinis berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1451) { // Foreign key constraint violation
                return redirect()->back()->with('error', 'Gagal menghapus kategori klinis. Kategori ini sedang digunakan.');
            } else {
                return redirect()->back()->with('error', 'Gagal menghapus kategori klinis. Terjadi kesalahan database.');
            }
        }
    }

    public function storeKodeTindakanTerapi(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:kode_tindakan_terapi,kode',
            'deskripsi_tindakan_terapi' => 'required',
            'idkategori' => 'required|exists:kategori,idkategori',
            'idkategori_klinis' => 'required|exists:kategori_klinis,idkategori_klinis',
        ]);

        KodeTindakanTerapi::create($request->all());

        return redirect()->back()->with('success', 'Kode tindakan terapi berhasil ditambahkan.');
    }

    public function updateKodeTindakanTerapi(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|unique:kode_tindakan_terapi,kode,' . $id . ',idkode_tindakan_terapi',
            'deskripsi_tindakan_terapi' => 'required',
            'idkategori' => 'required|exists:kategori,idkategori',
            'idkategori_klinis' => 'required|exists:kategori_klinis,idkategori_klinis',
        ]);

        $kode = KodeTindakanTerapi::findOrFail($id);
        $kode->update($request->all());

        return redirect()->back()->with('success', 'Kode tindakan terapi berhasil diperbarui.');
    }

    public function destroyKodeTindakanTerapi($id)
    {
        try {
            $kode = KodeTindakanTerapi::findOrFail($id);
            $kode->delete();
            return redirect()->back()->with('success', 'Kode tindakan terapi berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1451) { // Foreign key constraint violation
                return redirect()->back()->with('error', 'Gagal menghapus kode tindakan terapi. Kode ini sedang digunakan.');
            } else {
                return redirect()->back()->with('error', 'Gagal menghapus kode tindakan terapi. Terjadi kesalahan database.');
            }
        }
    }

    public function storePemilik(Request $request)
    {
        $request->validate([
            'nama_user' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'no_wa' => 'required',
            'alamat' => 'nullable',
        ]);

        $user = User::create([
            'nama' => $request->nama_user,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'idrole' => 2, // Assuming 2 is the role for Pemilik
        ]);

        Pemilik::create([
            'iduser' => $user->iduser,
            'no_wa' => $request->no_wa,
            'alamat' => $request->alamat,
        ]);

        return redirect()->back()->with('success', 'Pemilik berhasil ditambahkan.');
    }

    public function updatePemilik(Request $request, $id)
    {
        $pemilik = Pemilik::findOrFail($id);
        $user = $pemilik->user;

        $request->validate([
            'nama_user' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->iduser . ',iduser',
            'no_wa' => 'required',
            'alamat' => 'nullable',
        ]);

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
    }

    public function destroyPemilik($id)
    {
        $pemilik = Pemilik::findOrFail($id);
        $pemilik->user->delete();
        $pemilik->delete();

        return redirect()->back()->with('success', 'Pemilik berhasil dihapus.');
    }

    public function storePet(Request $request)
    {
        $request->validate([
            'idpemilik' => 'required|exists:pemilik,idpemilik',
            'nama_pet' => 'required',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required',
            'idras_hewan' => 'required|exists:ras_hewan,idras',
        ]);

        Pet::create($request->all());

        return redirect()->back()->with('success', 'Pet berhasil ditambahkan.');
    }

    public function updatePet(Request $request, $id)
    {
        $request->validate([
            'idpemilik' => 'required|exists:pemilik,idpemilik',
            'nama_pet' => 'required',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required',
            'idras_hewan' => 'required|exists:ras_hewan,idras',
        ]);

        $pet = Pet::findOrFail($id);
        $pet->update($request->all());

        return redirect()->back()->with('success', 'Pet berhasil diperbarui.');
    }

    public function destroyPet($id)
    {
        $pet = Pet::findOrFail($id);
        $pet->delete();

        return redirect()->back()->with('success', 'Pet berhasil dihapus.');
    }

    public function getPetData($id)
    {
        $pet = Pet::with(['pemilik', 'rasHewan.jenisHewan'])->findOrFail($id);
        return response()->json($pet);
    }
}

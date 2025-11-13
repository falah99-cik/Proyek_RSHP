<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriKlinis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriKlinisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoriKlinis = KategoriKlinis::all();
        return view('admin.kategori-klinis.index', compact('kategoriKlinis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori_klinis' => 'required|string|max:255|unique:kategori_klinis,nama_kategori_klinis'
        ]);

        try {
            KategoriKlinis::create([
                'nama_kategori_klinis' => $request->nama_kategori_klinis
            ]);

            return redirect()->route('admin.kategori-klinis.index')
                ->with('success', "Kategori Klinis **{$request->nama_kategori_klinis}** berhasil ditambahkan!");
        } catch (\Exception $e) {
            return redirect()->route('admin.kategori-klinis.index')
                ->with('error', 'Gagal menambahkan kategori klinis. Error: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori_klinis' => 'required|string|max:255|unique:kategori_klinis,nama_kategori_klinis,' . $id . ',idkategori_klinis'
        ]);

        try {
            $kategoriKlinis = KategoriKlinis::findOrFail($id);
            $oldName = $kategoriKlinis->nama_kategori_klinis;

            $kategoriKlinis->update([
                'nama_kategori_klinis' => $request->nama_kategori_klinis
            ]);

            return redirect()->route('admin.kategori-klinis.index')
                ->with('success', "Kategori Klinis ID **{$id}** berhasil diperbarui dari **{$oldName}** menjadi **{$request->nama_kategori_klinis}**.");
        } catch (\Exception $e) {
            return redirect()->route('admin.kategori-klinis.index')
                ->with('error', 'Gagal memperbarui kategori klinis. Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $kategoriKlinis = KategoriKlinis::findOrFail($id);

            // Check if kategori klinis is being used by any kode tindakan terapi
            if ($kategoriKlinis->kodeTindakanTerapi()->exists()) {
                return redirect()->route('admin.kategori-klinis.index')
                    ->with('error', 'Gagal menghapus kategori klinis. Kategori ini sedang digunakan oleh beberapa kode tindakan terapi.');
            }

            $kategoriName = $kategoriKlinis->nama_kategori_klinis;
            $kategoriKlinis->delete();

            return redirect()->route('admin.kategori-klinis.index')
                ->with('success', "Kategori Klinis **{$kategoriName}** berhasil dihapus.");
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.kategori-klinis.index')
                ->with('error', "Kategori Klinis ID **{$id}** tidak ditemukan.");
        } catch (\Exception $e) {
            return redirect()->route('admin.kategori-klinis.index')
                ->with('error', 'Gagal menghapus kategori klinis. Error: ' . $e->getMessage());
        }
    }
}

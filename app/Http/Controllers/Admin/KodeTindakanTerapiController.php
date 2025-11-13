<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KodeTindakanTerapi;
use App\Models\Kategori;
use App\Models\KategoriKlinis;
use Illuminate\Http\Request;

class KodeTindakanTerapiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kodeTindakanTerapi = KodeTindakanTerapi::with(['kategori', 'kategoriKlinis'])->get();
        $kategoris = Kategori::all();
        $kategoriKlinis = KategoriKlinis::all();

        return view('admin.kode-tindakan-terapi.index', compact('kodeTindakanTerapi', 'kategoris', 'kategoriKlinis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:255|unique:kode_tindakan_terapi,kode',
            'deskripsi_tindakan_terapi' => 'required|string|max:500',
            'idkategori' => 'required|integer|exists:kategori,idkategori',
            'idkategori_klinis' => 'required|integer|exists:kategori_klinis,idkategori_klinis'
        ]);

        try {
            KodeTindakanTerapi::create([
                'kode' => $request->kode,
                'deskripsi_tindakan_terapi' => $request->deskripsi_tindakan_terapi,
                'idkategori' => $request->idkategori,
                'idkategori_klinis' => $request->idkategori_klinis
            ]);

            return redirect()->route('admin.kode-tindakan-terapi.index')
                ->with('success', "Kode Tindakan Terapi **{$request->kode}** berhasil ditambahkan!");
        } catch (\Exception $e) {
            return redirect()->route('admin.kode-tindakan-terapi.index')
                ->with('error', 'Gagal menambahkan kode tindakan terapi. Error: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:255|unique:kode_tindakan_terapi,kode,' . $id . ',idkode_tindakan_terapi',
            'deskripsi_tindakan_terapi' => 'required|string|max:500',
            'idkategori' => 'required|integer|exists:kategori,idkategori',
            'idkategori_klinis' => 'required|integer|exists:kategori_klinis,idkategori_klinis'
        ]);

        try {
            $kodeTindakanTerapi = KodeTindakanTerapi::findOrFail($id);
            $oldKode = $kodeTindakanTerapi->kode;

            $kodeTindakanTerapi->update([
                'kode' => $request->kode,
                'deskripsi_tindakan_terapi' => $request->deskripsi_tindakan_terapi,
                'idkategori' => $request->idkategori,
                'idkategori_klinis' => $request->idkategori_klinis
            ]);

            return redirect()->route('admin.kode-tindakan-terapi.index')
                ->with('success', "Kode Tindakan Terapi ID **{$id}** (**{$oldKode}**) berhasil diperbarui.");
        } catch (\Exception $e) {
            return redirect()->route('admin.kode-tindakan-terapi.index')
                ->with('error', 'Gagal memperbarui kode tindakan terapi. Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $kodeTindakanTerapi = KodeTindakanTerapi::findOrFail($id);

            // Check if kode tindakan terapi is being used by any detail rekam medis
            if ($kodeTindakanTerapi->detailRekamMedis()->exists()) {
                return redirect()->route('admin.kode-tindakan-terapi.index')
                    ->with('error', 'Gagal menghapus kode tindakan terapi. Kode ini sedang digunakan oleh beberapa detail rekam medis.');
            }

            $kodeName = $kodeTindakanTerapi->kode;
            $kodeTindakanTerapi->delete();

            return redirect()->route('admin.kode-tindakan-terapi.index')
                ->with('success', "Kode Tindakan Terapi **{$kodeName}** berhasil dihapus.");
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.kode-tindakan-terapi.index')
                ->with('error', "Kode Tindakan Terapi ID **{$id}** tidak ditemukan.");
        } catch (\Exception $e) {
            return redirect()->route('admin.kode-tindakan-terapi.index')
                ->with('error', 'Gagal menghapus kode tindakan terapi. Error: ' . $e->getMessage());
        }
    }
}

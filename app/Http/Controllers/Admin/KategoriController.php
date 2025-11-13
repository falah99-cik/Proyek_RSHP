<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoris = Kategori::all();
        return view('admin.kategori.index', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori'
        ]);

        try {
            Kategori::create([
                'nama_kategori' => $request->nama_kategori
            ]);

            return redirect()->route('admin.kategori.index')
                ->with('success', "Kategori **{$request->nama_kategori}** berhasil ditambahkan!");
        } catch (\Exception $e) {
            return redirect()->route('admin.kategori.index')
                ->with('error', 'Gagal menambahkan kategori. Error: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori,' . $id . ',idkategori'
        ]);

        try {
            $kategori = Kategori::findOrFail($id);
            $oldName = $kategori->nama_kategori;

            $kategori->update([
                'nama_kategori' => $request->nama_kategori
            ]);

            return redirect()->route('admin.kategori.index')
                ->with('success', "Kategori ID **{$id}** berhasil diperbarui dari **{$oldName}** menjadi **{$request->nama_kategori}**.");
        } catch (\Exception $e) {
            return redirect()->route('admin.kategori.index')
                ->with('error', 'Gagal memperbarui kategori. Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $kategori = Kategori::findOrFail($id);

            // Check if kategori is being used by any kode tindakan terapi
            if ($kategori->kodeTindakanTerapi()->exists()) {
                return redirect()->route('admin.kategori.index')
                    ->with('error', 'Gagal menghapus kategori. Kategori ini sedang digunakan oleh beberapa kode tindakan terapi.');
            }

            $kategoriName = $kategori->nama_kategori;
            $kategori->delete();

            return redirect()->route('admin.kategori.index')
                ->with('success', "Kategori **{$kategoriName}** berhasil dihapus.");
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.kategori.index')
                ->with('error', "Kategori ID **{$id}** tidak ditemukan.");
        } catch (\Exception $e) {
            return redirect()->route('admin.kategori.index')
                ->with('error', 'Gagal menghapus kategori. Error: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RasHewan;
use App\Models\JenisHewan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RasHewanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rasHewan = RasHewan::with('jenisHewan')->orderBy('idras_hewan', 'asc')->get();
        return view('admin.ras-hewan.index', compact('rasHewan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenisHewan = JenisHewan::orderBy('nama_jenis_hewan', 'asc')->get();
        return view('admin.ras-hewan.create', compact('jenisHewan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_ras' => 'required|string|max:100',
            'idjenis_hewan' => 'required|exists:jenis_hewan,idjenis_hewan'
        ]);

        $result = RasHewan::create([
            'nama_ras' => $request->nama_ras,
            'idjenis_hewan' => $request->idjenis_hewan
        ]);

        if ($result['status'] === 'success') {
            return redirect()->route('admin.ras-hewan.index')->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message'])->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $rasHewan = RasHewan::findOrFail($id);
        $jenisHewan = JenisHewan::orderBy('nama_jenis_hewan', 'asc')->get();
        return view('admin.ras-hewan.edit', compact('rasHewan', 'jenisHewan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_ras' => 'required|string|max:100',
            'idjenis_hewan' => 'required|exists:jenis_hewan,idjenis_hewan'
        ]);

        $rasHewan = RasHewan::findOrFail($id);

        // Check for duplicate
        if (RasHewan::where('nama_ras', $request->nama_ras)
            ->where('idjenis_hewan', $request->idjenis_hewan)
            ->where('idras_hewan', '!=', $id)
            ->exists()
        ) {
            return redirect()->back()
                ->with('error', 'Ras dengan nama tersebut sudah ada untuk jenis hewan ini.')
                ->withInput();
        }

        $rasHewan->update([
            'nama_ras' => $request->nama_ras,
            'idjenis_hewan' => $request->idjenis_hewan
        ]);

        return redirect()->route('admin.ras-hewan.index')
            ->with('success', 'Ras hewan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Check if this breed is being used by any pets
        $hasPets = DB::table('pet')
            ->where('idras_hewan', $id)
            ->exists();

        if ($hasPets) {
            return redirect()->route('admin.ras-hewan.index')
                ->with('error', 'Ras hewan ini tidak dapat dihapus karena masih digunakan oleh data pet.');
        }

        $rasHewan = RasHewan::findOrFail($id);

        try {
            $rasHewan->delete();
            return redirect()->route('admin.ras-hewan.index')
                ->with('success', 'Ras hewan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.ras-hewan.index')
                ->with('error', 'Gagal menghapus ras hewan: ' . $e->getMessage());
        }
    }

    /**
     * Get breeds by animal type (API endpoint)
     */
    public function getByJenis($jenisHewanId)
    {
        try {
            $rasHewan = RasHewan::where('idjenis_hewan', $jenisHewanId)
                ->orderBy('nama_ras', 'asc')
                ->get();

            return response()->json($rasHewan);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengambil data ras hewan'
            ], 500);
        }
    }
}

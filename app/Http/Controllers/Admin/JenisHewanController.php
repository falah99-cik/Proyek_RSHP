<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisHewan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JenisHewanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jenisHewan = JenisHewan::orderBy('idjenis_hewan', 'asc')->get();
        return view('admin.jenis-hewan.index', compact('jenisHewan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.jenis-hewan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_jenis_hewan' => 'required|string|max:50|unique:jenis_hewan,nama_jenis_hewan'
        ]);

        $result = JenisHewan::create([
            'nama_jenis_hewan' => $request->nama_jenis_hewan
        ]);

        if ($result['status'] === 'success') {
            return redirect()->route('admin.jenis-hewan.index')->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message'])->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jenisHewan = JenisHewan::findOrFail($id);
        return view('admin.jenis-hewan.edit', compact('jenisHewan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jenis_hewan' => 'required|string|max:50|unique:jenis_hewan,nama_jenis_hewan,' . $id . ',idjenis_hewan'
        ]);

        $result = JenisHewan::updateJenisHewan([
            'nama_jenis_hewan' => $request->nama_jenis_hewan
        ], $id);

        if ($result['status'] === 'success') {
            return redirect()->route('admin.jenis-hewan.index')->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message'])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Check if this animal type is being used by any breeds (ras_hewan)
        $hasBreeds = DB::table('ras_hewan')
            ->where('idjenis_hewan', $id)
            ->exists();

        if ($hasBreeds) {
            return redirect()->route('admin.jenis-hewan.index')
                ->with('error', 'Jenis hewan ini tidak dapat dihapus karena masih digunakan oleh data ras hewan.');
        }

        // Check if this animal type is being used by any pets
        $hasPets = DB::table('pet')
            ->join('ras_hewan', 'pet.idras_hewan', '=', 'ras_hewan.idras_hewan')
            ->where('ras_hewan.idjenis_hewan', $id)
            ->exists();

        if ($hasPets) {
            return redirect()->route('admin.jenis-hewan.index')
                ->with('error', 'Jenis hewan ini tidak dapat dihapus karena masih digunakan oleh data pet.');
        }

        $result = JenisHewan::deleteJenisHewan($id);

        if ($result['status'] === 'success') {
            return redirect()->route('admin.jenis-hewan.index')
                ->with('success', $result['message']);
        } else {
            return redirect()->route('admin.jenis-hewan.index')
                ->with('error', $result['message']);
        }
    }
}

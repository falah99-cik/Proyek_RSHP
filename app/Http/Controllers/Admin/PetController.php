<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\Pemilik;
use App\Models\RasHewan;
use App\Models\JenisHewan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetController extends Controller
{
    public function index()
    {
        $pets = Pet::getAllPetsWithDetails();
        $owners = Pemilik::with('user')->get();
        $jenisHewan = JenisHewan::all();

        return view('admin.pet.index', compact('pets', 'owners', 'jenisHewan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'idpemilik' => 'required|exists:pemilik,idpemilik',
            'nama_pet' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Jantan,Betina',
            'idras_hewan' => 'required|exists:ras_hewan,idras_hewan',
            'warna_tanda' => 'nullable|string|max:255'
        ], [
            'idpemilik.required' => 'Pemilik wajib dipilih',
            'nama_pet.required' => 'Nama pet wajib diisi',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
            'tanggal_lahir.date' => 'Format tanggal tidak valid',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'jenis_kelamin.in' => 'Jenis kelamin harus Jantan atau Betina',
            'idras_hewan.required' => 'Ras hewan wajib dipilih',
            'idras_hewan.exists' => 'Ras hewan tidak valid'
        ]);

        try {
            DB::beginTransaction();

            Pet::create([
                'idpemilik' => $validated['idpemilik'],
                'nama' => $validated['nama_pet'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'idras_hewan' => $validated['idras_hewan'],
                'warna_tanda' => $validated['warna_tanda'] ?? ''
            ]);

            DB::commit();

            return redirect()->route('admin.pet.index')
                ->with('success', 'Pet berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan pet: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'idpemilik' => 'required|exists:pemilik,idpemilik',
            'nama_pet' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Jantan,Betina',
            'idras_hewan' => 'required|exists:ras_hewan,idras_hewan',
            'warna_tanda' => 'nullable|string|max:255'
        ], [
            'idpemilik.required' => 'Pemilik wajib dipilih',
            'nama_pet.required' => 'Nama pet wajib diisi',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
            'tanggal_lahir.date' => 'Format tanggal tidak valid',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'jenis_kelamin.in' => 'Jenis kelamin harus Jantan atau Betina',
            'idras_hewan.required' => 'Ras hewan wajib dipilih',
            'idras_hewan.exists' => 'Ras hewan tidak valid'
        ]);

        try {
            DB::beginTransaction();

            $pet = Pet::findOrFail($id);
            $pet->update([
                'idpemilik' => $validated['idpemilik'],
                'nama' => $validated['nama_pet'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'idras_hewan' => $validated['idras_hewan'],
                'warna_tanda' => $validated['warna_tanda'] ?? ''
            ]);

            DB::commit();

            return redirect()->route('admin.pet.index')
                ->with('success', 'Pet berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui pet: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $pet = Pet::findOrFail($id);

            // Check for associated medical records
            $hasMedicalRecords = DB::table('rekam_medis')
                ->where('idpet', $id)
                ->exists();

            if ($hasMedicalRecords) {
                return redirect()->route('admin.pet.index')
                    ->with('error', 'Pet tidak dapat dihapus karena memiliki rekam medis');
            }

            // Check for associated appointments
            $hasAppointments = DB::table('temu_dokter')
                ->where('idpet', $id)
                ->exists();

            if ($hasAppointments) {
                return redirect()->route('admin.pet.index')
                    ->with('error', 'Pet tidak dapat dihapus karena memiliki janji temu dokter');
            }

            $pet->delete();

            DB::commit();

            return redirect()->route('admin.pet.index')
                ->with('success', 'Pet berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.pet.index')
                ->with('error', 'Gagal menghapus pet: ' . $e->getMessage());
        }
    }

    public function getPetData($id)
    {
        try {
            $pet = Pet::with(['owner.user', 'rasHewan.jenisHewan'])->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'idpet' => $pet->idpet,
                    'idpemilik' => $pet->idpemilik,
                    'nama' => $pet->nama,
                    'tanggal_lahir' => $pet->tanggal_lahir,
                    'jenis_kelamin' => $pet->jenis_kelamin,
                    'warna_tanda' => $pet->warna_tanda,
                    'idras_hewan' => $pet->idras_hewan,
                    'idjenis_hewan' => $pet->rasHewan->jenisHewan->idjenis_hewan
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pet tidak ditemukan'
            ], 404);
        }
    }
}

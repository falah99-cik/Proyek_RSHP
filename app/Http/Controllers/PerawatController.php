<?php

namespace App\Http\Controllers;

use App\Models\DetailRekamMedis;
use App\Models\RekamMedis;
use App\Models\User;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PerawatController extends Controller
{
    public function dashboard()
    {
        $perawatId = Auth::user()->iduser;

        // Statistik rekam medis yang ditangani perawat ini
        $totalRekamMedis = DetailRekamMedis::where('idperawat', $perawatId)->count();
        $rekamMedisHariIni = DetailRekamMedis::where('idperawat', $perawatId)
            ->whereDate('created_at', today())
            ->count();

        // Rekam medis yang perlu ditangani (status tertentu)
        $rekamMedisAktif = RekamMedis::where('status', 'perawatan')
            ->with(['pet', 'pemilik'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('perawat.dashboard', compact('totalRekamMedis', 'rekamMedisHariIni', 'rekamMedisAktif'));
    }

    public function rekamMedis()
    {
        $rekamMedisList = RekamMedis::with(['pet', 'pemilik', 'dokter'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('perawat.rekam_medis', compact('rekamMedisList'));
    }

    public function detailRekamMedis($id)
    {
        $rekamMedis = RekamMedis::with(['pet', 'pemilik', 'dokter', 'detailRekamMedis'])
            ->findOrFail($id);

        return view('perawat.detail_rekam_medis', compact('rekamMedis'));
    }

    public function storeDetailRekamMedis(Request $request)
    {
        // Validasi input
        $request->validate([
            'idrekam_medis' => 'required|exists:rekam_medis,idrekam_medis',
            'idkode_tindakan_terapi' => 'required|exists:kode_tindakan_terapi,idkode_tindakan_terapi',
            'detail' => 'nullable|string',
            'pet_id' => 'required|exists:pet,idpet',
        ]);

        try {
            DB::beginTransaction();

            // Buat detail rekam medis baru
            DetailRekamMedis::create([
                'idrekam_medis' => $request->idrekam_medis,
                'idkode_tindakan_terapi' => $request->idkode_tindakan_terapi,
                'detail' => $request->detail ?? '',
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Tindakan/terapi berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan tindakan/terapi: ' . $e->getMessage())->withInput();
        }
    }

    public function destroyDetailRekamMedis($id)
    {
        // Validasi input dari GET request
        $iddetail_rekam_medis = request()->get('iddetail_rekam_medis', $id);
        $pet_id = request()->get('pet_id');

        if (empty($iddetail_rekam_medis) || empty($pet_id)) {
            return redirect()->back()->with('error', 'ID data tidak valid.');
        }

        try {
            DB::beginTransaction();

            // Cari dan hapus detail rekam medis
            $detail = DetailRekamMedis::findOrFail($iddetail_rekam_medis);
            $detail->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Tindakan/terapi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus tindakan/terapi: ' . $e->getMessage());
        }
    }

    public function storeRekamMedisUtama(Request $request)
    {
        $request->validate([
            'pet_id' => 'required|exists:pet,idpet',
            'anamnesa' => 'required|string',
            'temuan_klinis' => 'required|string',
            'diagnosa' => 'required|string',
            'iduser_dokter' => 'required|exists:users,iduser',
        ]);

        try {
            DB::beginTransaction();

            // Get the role_user ID for the selected doctor (idrole = 2 for Dokter)
            $dokterRole = DB::table('role_user')
                ->where('iduser', $request->iduser_dokter)
                ->where('idrole', 2) // 2 is the role ID for Dokter
                ->first();

            if (!$dokterRole) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Dokter pemeriksa tidak valid.')
                    ->withInput();
            }

            // Create the main medical record
            RekamMedis::create([
                'idpet' => $request->pet_id,
                'anamnesa' => $request->anamnesa,
                'temuan_klinis' => $request->temuan_klinis,
                'diagnosa' => $request->diagnosa,
                'dokter_pemeriksa' => $dokterRole->idrole_user,
            ]);

            DB::commit();

            return redirect()->route('perawat.rekam-medis', ['pet_id' => $request->pet_id])
                ->with('success', 'Rekam medis berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating rekam medis utama: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Gagal menambahkan rekam medis: ' . $e->getMessage())
                ->withInput();
        }
    }
}

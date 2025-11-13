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
        $totalPasien = Pet::count();
        $totalRekamMedis = RekamMedis::count();
        $pasienTerbaru = DB::table('rekam_medis AS rm')
            ->join('pet AS p', 'rm.idpet', '=', 'p.idpet')
            ->join('pemilik AS pm', 'p.idpemilik', '=', 'pm.idpemilik')
            ->join('user AS u', 'pm.iduser', '=', 'u.iduser')
            ->select(
                'p.idpet',
                'p.nama AS nama_pet',
                'u.nama AS nama_pemilik',
                DB::raw('MAX(rm.created_at) AS last_updated')
            )
            ->groupBy('p.idpet', 'p.nama', 'u.nama')
            ->orderBy('last_updated', 'DESC')
            ->limit(10)
            ->get();

        return view('perawat.dashboard', compact(
            'totalPasien',
            'totalRekamMedis',
            'pasienTerbaru'
        ));
    }

    public function tambahRekamMedis($id)
    {
        // ambil pet + data pemilik (via user)
        $pet = Pet::with(['pemilik.user'])->findOrFail($id);

        return view('perawat.tambah_rekam_medis', compact('pet'));
    }

    public function rekamMedis()
    {
        $rekamMedisList = RekamMedis::with(['pet.pemilik.user', 'dokter'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('perawat.rekam_medis', compact('rekamMedisList'));
    }

    public function detailRekamMedis($id)
    {
        $rekamMedis = RekamMedis::with(['pet.pemilik.user', 'dokter', 'detailRekamMedis'])
            ->findOrFail($id);

        return view('perawat.detail_rekam_medis', compact('rekamMedis'));
    }

    public function storeDetailRekamMedis(Request $request)
    {
        $request->validate([
            'idrekam_medis' => 'required|exists:rekam_medis,idrekam_medis',
            'idkode_tindakan_terapi' => 'required|exists:kode_tindakan_terapi,idkode_tindakan_terapi',
            'detail' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            DetailRekamMedis::create([
                'idrekam_medis' => $request->idrekam_medis,
                'idkode_tindakan_terapi' => $request->idkode_tindakan_terapi,
                'detail' => $request->detail ?? '',
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Tindakan/terapi berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Gagal menambahkan tindakan/terapi: ' . $e->getMessage());
        }
    }

    public function destroyDetailRekamMedis($id)
    {
        try {
            DB::beginTransaction();

            $detail = DetailRekamMedis::findOrFail($id);
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
            'iduser_dokter' => 'required|exists:user,iduser',
        ]);

        try {
            DB::beginTransaction();

            // cek bahwa user tsb memang dokter (role id = 2)
            $dokterRole = DB::table('role_user')
                ->where('iduser', $request->iduser_dokter)
                ->where('idrole', 2)
                ->first();

            if (!$dokterRole) {
                return redirect()->back()
                    ->with('error', 'Dokter pemeriksa tidak valid.')
                    ->withInput();
            }

            RekamMedis::create([
                'idpet' => $request->pet_id,
                'anamnesa' => $request->anamnesa,
                'temuan_klinis' => $request->temuan_klinis,
                'diagnosa' => $request->diagnosa,
                'dokter_pemeriksa' => $dokterRole->idrole_user,
            ]);

            DB::commit();

            return redirect()->route('perawat.rekam-medis')
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

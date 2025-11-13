<?php

namespace App\Http\Controllers;

use App\Models\RekamMedis;
use App\Models\TemuDokter;
use App\Models\ReservasiDokter;
use App\Models\Dokter;
use App\Models\DetailRekamMedis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DokterController extends Controller
{
    public function dashboard()
    {
        $dokterId = Auth::user()->iduser;

        // Get role_user_id for dokter
        $dokter = new Dokter();
        $idrole_user_dokter = $dokter->getRoleUserIdDokter($dokterId);

        // Jadwal hari ini
        $reservasiHariIni = TemuDokter::where('idrole_user', $idrole_user_dokter)
            ->whereDate('waktu_daftar', today())
            ->where('status', 0)
            ->get();

        // Statistik
        $totalReservasiHariIni = $reservasiHariIni->count();
        $totalRekamMedis = RekamMedis::where('dokter_pemeriksa', $idrole_user_dokter)->count();

        return view('dokter.dashboard', compact('reservasiHariIni', 'totalReservasiHariIni', 'totalRekamMedis'));
    }

    public function jadwalPemeriksaan()
    {
        $dokterId = Auth::user()->iduser;

        // Get role_user_id for dokter
        $dokter = new Dokter();
        $idrole_user_dokter = $dokter->getRoleUserIdDokter($dokterId);

        $jadwalPemeriksaan = TemuDokter::where('idrole_user', $idrole_user_dokter)
            ->where('status', 0)
            ->orderBy('waktu_daftar')
            ->get();

        return view('dokter.jadwal-pemeriksaan', compact('jadwalPemeriksaan'));
    }

    public function riwayatRekamMedis()
    {
        $dokterId = Auth::user()->iduser;

        // Get role_user_id for dokter
        $dokter = new Dokter();
        $idrole_user_dokter = $dokter->getRoleUserIdDokter($dokterId);

        $riwayatRekamMedis = RekamMedis::where('dokter_pemeriksa', $idrole_user_dokter)
            ->with(['pet.pemilik.user', 'pet.jenisHewan', 'pet.rasHewan'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dokter.riwayat-rekam-medis', compact('riwayatRekamMedis'));
    }

    public function tambahRekamMedis($idreservasi)
    {
        $dokterId = Auth::user()->iduser;

        // Get role_user_id for dokter
        $dokter = new Dokter();
        $idrole_user_dokter = $dokter->getRoleUserIdDokter($dokterId);

        $reservasiDokter = new ReservasiDokter();
        $reservasi = $reservasiDokter->getReservasiDetail($idreservasi, $idrole_user_dokter);

        if (!$reservasi) {
            return redirect()->route('dokter.jadwal-pemeriksaan')->with('error', 'Reservasi tidak ditemukan.');
        }

        return view('dokter.tambah-rekam-medis', compact('reservasi'));
    }

    public function storeRekamMedis(Request $request)
    {
        $request->validate([
            'idreservasi_dokter' => 'required|exists:temu_dokter,idreservasi_dokter',
            'idpet' => 'required|exists:pet,idpet',
            'anamnesa' => 'required',
            'temuan_klinis' => 'required',
            'diagnosa' => 'required',
            'prognosa' => 'nullable',
        ]);

        $dokterId = Auth::user()->iduser;

        // Get role_user_id for dokter
        $dokter = new Dokter();
        $idrole_user_dokter = $dokter->getRoleUserIdDokter($dokterId);

        DB::beginTransaction();
        try {
            RekamMedis::create([
                'anamnesa' => $request->anamnesa,
                'temuan_klinis' => $request->temuan_klinis,
                'diagnosa' => $request->diagnosa,
                'prognosa' => $request->prognosa,
                'idpet' => $request->idpet,
                'dokter_pemeriksa' => $idrole_user_dokter,
                'idreservasi_dokter' => $request->idreservasi_dokter,
            ]);

            // Update status reservasi menjadi selesai
            $temuDokter = TemuDokter::where('idreservasi_dokter', $request->idreservasi_dokter)->first();
            if ($temuDokter) {
                $temuDokter->update(['status' => 1]);
            }

            DB::commit();

            return redirect()->route('dokter.jadwal-pemeriksaan')->with('success', 'Rekam medis berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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
}

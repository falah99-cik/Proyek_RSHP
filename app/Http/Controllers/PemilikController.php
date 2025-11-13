<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\RekamMedis;
use App\Models\TemuDokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemilikController extends Controller
{
    public function dashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $pemilik = $user->pemilik;

        if (!$pemilik) {
            return redirect()->route('login')->with('error', 'Data pemilik tidak ditemukan.');
        }

        $pemilikId = $pemilik->idpemilik;

        $totalHewan = $pemilik->pets()->count();

        $totalRekamMedis = RekamMedis::whereHas(
            'pet',
            fn($q) =>
            $q->where('idpemilik', $pemilikId)
        )->count();


        $reservasiAktif = TemuDokter::with(['pet', 'dokter.user'])
            ->whereHas('pet', fn($q) => $q->where('idpemilik', $pemilikId))
            ->orderBy('waktu_daftar', 'desc')
            ->limit(5)
            ->get();

        $hewanTerakhir = $pemilik->pets()
            ->orderBy('idpet', 'desc')
            ->first();

        $pets = $pemilik->pets()->orderBy('idpet', 'desc')->get();

        return view('pemilik.dashboard', compact(
            'totalHewan',
            'totalRekamMedis',
            'reservasiAktif',
            'hewanTerakhir',
            'pets'
        ));
    }

    public function daftarPet()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $pemilik = $user->pemilik;

        $pets = $pemilik->pets()
            ->with(['rasHewan', 'jenisHewan'])
            ->orderBy('idpet', 'desc')
            ->get();

        return view('pemilik.daftar_pet', compact('pets'));
    }

    public function daftarRekamMedis()
    {
        $pemilikId = Auth::user()->pemilik->idpemilik;

        $rekamMedisList = RekamMedis::with(['pet', 'dokter.user'])
            ->whereHas('pet', fn($q) => $q->where('idpemilik', $pemilikId))
            ->orderBy('idrekam_medis', 'desc')
            ->get();

        return view('pemilik.daftar_rekam_medis', compact('rekamMedisList'));
    }

    public function daftarReservasi()
    {
        $pemilikId = Auth::user()->pemilik->idpemilik;

        $reservasiList = TemuDokter::with(['pet', 'dokter.user'])
            ->whereHas('pet', fn($q) => $q->where('idpemilik', $pemilikId))
            ->orderBy('waktu_daftar', 'desc')
            ->get();

        return view('pemilik.daftar_reservasi', compact('reservasiList'));
    }

    public function profil()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return view('pemilik.profil_pemilik', [
            'user'    => $user,
            'pemilik' => $user->pemilik
        ]);
    }

    public function editProfil()
    {
        $user = Auth::user();

        return view('pemilik.edit_profil', [
            'user'    => $user,
            'pemilik' => $user->pemilik
        ]);
    }

    public function updateProfil(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $pemilik = $user->pemilik;

        $request->validate([
            'nama'   => 'required|string|max:255',
            'email'  => 'required|email|max:255|unique:user,email,' . $user->iduser . ',iduser',
            'no_wa'  => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
        ]);

        $user->update([
            'nama'  => $request->nama,
            'email' => $request->email,
        ]);

        $pemilik->update([
            'no_wa'  => $request->no_wa,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('pemilik.profil')->with('success', 'Profil berhasil diperbarui.');
    }

    public function detailRekamMedis($id)
    {
        $pemilikId = Auth::user()->pemilik->idpemilik;

        $rekamMedis = RekamMedis::with(['pet', 'dokter.user', 'detailRekamMedis.tindakan'])
            ->whereHas('pet', fn($q) => $q->where('idpemilik', $pemilikId))
            ->findOrFail($id);

        return view('pemilik.detail_rekam_medis', compact('rekamMedis'));
    }
}

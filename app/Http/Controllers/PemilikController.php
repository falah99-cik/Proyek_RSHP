<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\RekamMedis;
use App\Models\TemuDokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemilikController extends Controller
{
    public function dashboard()
    {
        $pemilikId = auth()->user()->pemilik->idpemilik ?? null;

        if (!$pemilikId) {
            return redirect()->route('login')->with('error', 'Data pemilik tidak ditemukan.');
        }

        // Statistik hewan peliharaan
        $totalHewan = Pet::where('idpemilik', $pemilikId)->count();
        $totalRekamMedis = RekamMedis::whereHas('pet', function ($query) use ($pemilikId) {
            $query->where('idpemilik', $pemilikId);
        })->count();

        // Reservasi aktif
        $reservasiAktif = TemuDokter::whereHas('pet', function ($query) use ($pemilikId) {
            $query->where('idpemilik', $pemilikId);
        })->where('status', 0)
            ->with(['pet', 'dokter'])
            ->orderBy('tanggal_temu')
            ->get();

        // Hewan terakhir
        $hewanTerakhir = Pet::where('idpemilik', $pemilikId)
            ->orderBy('created_at', 'desc')
            ->first();

        return view('pemilik.dashboard', compact('totalHewan', 'totalRekamMedis', 'reservasiAktif', 'hewanTerakhir'));
    }

    public function daftarPet()
    {
        $pemilikId = auth()->user()->pemilik->idpemilik ?? null;

        if (!$pemilikId) {
            return redirect()->route('login')->with('error', 'Data pemilik tidak ditemukan.');
        }

        $pets = Pet::where('idpemilik', $pemilikId)
            ->with(['rasHewan', 'jenisHewan'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pemilik.daftar_pet', compact('pets'));
    }

    public function daftarRekamMedis()
    {
        $pemilikId = auth()->user()->pemilik->idpemilik ?? null;

        if (!$pemilikId) {
            return redirect()->route('login')->with('error', 'Data pemilik tidak ditemukan.');
        }

        $rekamMedisList = RekamMedis::whereHas('pet', function ($query) use ($pemilikId) {
            $query->where('idpemilik', $pemilikId);
        })->with(['pet', 'dokter'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pemilik.daftar_rekam_medis', compact('rekamMedisList'));
    }

    public function daftarReservasi()
    {
        $pemilikId = auth()->user()->pemilik->idpemilik ?? null;

        if (!$pemilikId) {
            return redirect()->route('login')->with('error', 'Data pemilik tidak ditemukan.');
        }

        $reservasiList = TemuDokter::whereHas('pet', function ($query) use ($pemilikId) {
            $query->where('idpemilik', $pemilikId);
        })->with(['pet', 'dokter'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pemilik.daftar_reservasi', compact('reservasiList'));
    }

    public function profil()
    {
        $user = auth()->user();
        $pemilik = $user->pemilik;

        if (!$pemilik) {
            return redirect()->route('login')->with('error', 'Data pemilik tidak ditemukan.');
        }

        return view('pemilik.profil_pemilik', compact('user', 'pemilik'));
    }

    public function editProfil()
    {
        $user = auth()->user();
        $pemilik = $user->pemilik;

        if (!$pemilik) {
            return redirect()->route('login')->with('error', 'Data pemilik tidak ditemukan.');
        }

        return view('pemilik.edit_profil', compact('user', 'pemilik'));
    }

    public function updateProfil(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:user,email,' . auth()->user()->iduser . ',iduser',
            'no_wa' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
        ]);

        $user = auth()->user();
        $pemilik = $user->pemilik;

        if (!$pemilik) {
            return redirect()->route('login')->with('error', 'Data pemilik tidak ditemukan.');
        }

        DB::transaction(function () use ($request, $user, $pemilik) {
            $user->update([
                'nama' => $request->nama,
                'email' => $request->email,
            ]);

            $pemilik->update([
                'no_wa' => $request->no_wa,
                'alamat' => $request->alamat,
            ]);
        });

        return redirect()->route('pemilik.profil')->with('success', 'Profil berhasil diperbarui.');
    }

    public function detailRekamMedis($id)
    {
        $rekamMedis = RekamMedis::with(['pet', 'dokter', 'detailRekamMedis'])
            ->findOrFail($id);

        // Pastikan rekam medis milik pemilik yang login
        if ($rekamMedis->pet->idpemilik != auth()->user()->pemilik->idpemilik) {
            return redirect()->route('pemilik.rekam-medis')->with('error', 'Akses ditolak.');
        }

        return view('pemilik.detail_rekam_medis', compact('rekamMedis'));
    }
}

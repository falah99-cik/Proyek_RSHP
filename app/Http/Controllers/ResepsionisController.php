<?php

namespace App\Http\Controllers;

use App\Models\TemuDokter;
use App\Models\User;
use App\Models\Pemilik;
use App\Models\Pet;
use App\Models\RekamMedis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ResepsionisController extends Controller
{
    public function dashboard()
    {
        // Statistik hari ini
        $totalAntrian = TemuDokter::whereDate('tanggal_temu', today())->count();
        $antrianSelesai = TemuDokter::whereDate('tanggal_temu', today())->where('status', 1)->count();
        $antrianMenunggu = TemuDokter::whereDate('tanggal_temu', today())->where('status', 0)->count();

        // Antrian hari ini
        $antrianHariIni = TemuDokter::whereDate('tanggal_temu', today())
            ->with(['pet', 'pemilik', 'dokter'])
            ->orderBy('no_urut')
            ->get();

        // Pasien terdaftar baru
        $pasienBaru = Pet::whereDate('created_at', today())->count();

        return view('resepsionis.dashboard', compact('totalAntrian', 'antrianSelesai', 'antrianMenunggu', 'antrianHariIni', 'pasienBaru'));
    }

    public function registrasiPemilik()
    {
        return view('resepsionis.registrasi_pemilik');
    }

    public function registrasiPet()
    {
        $pemilikList = Pemilik::with('user')->get();
        return view('resepsionis.registrasi_pet', compact('pemilikList'));
    }

    public function temuDokter()
    {
        $petList = Pet::with('pemilik')->get();
        $dokterList = User::whereHas('roles', function ($query) {
            $query->where('nama_role', 'Dokter');
        })->get();

        return view('resepsionis.temu_dokter', compact('petList', 'dokterList'));
    }

    public function manajemenRekamMedis()
    {
        $rekamMedisList = RekamMedis::with(['pet', 'pemilik', 'dokter'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('resepsionis.manajemen_rekam_medis', compact('rekamMedisList'));
    }

    public function storeTemuDokter(Request $request)
    {
        $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'dokter_id' => 'required|exists:users,id',
        ]);

        $tanggal = Carbon::now()->format('Y-m-d');
        $tanggalKode = Carbon::now()->format('Ymd');

        $lastUrut = TemuDokter::whereDate('waktu_daftar', $tanggal)->max('no_urut');
        $nextUrut = 1;
        if ($lastUrut) {
            $nextUrut = intval(substr($lastUrut, -3)) + 1;
        }

        $no_urut = $tanggalKode . '-' . str_pad($nextUrut, 3, '0', STR_PAD_LEFT);

        TemuDokter::create([
            'no_urut' => $no_urut,
            'status' => 0,
            'idpet' => $request->pet_id,
            'idrole_user' => $request->dokter_id,
        ]);

        $message = "Reservasi berhasil dibuat. Nomor antrean: " . $no_urut;
        return redirect()->back()->with('success', $message);
    }

    public function storePemilik(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'no_wa' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->roles()->attach(5); // 5 = Role Pemilik

        Pemilik::create([
            'iduser' => $user->id,
            'no_wa' => $request->no_wa,
            'alamat' => $request->alamat,
        ]);

        return redirect()->back()->with('success', 'Pemilik berhasil diregistrasi.');
    }

    public function storePet(Request $request)
    {
        $request->validate([
            'nama_pet' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string|max:10',
            'warna_tanda' => 'nullable|string',
            'idras_hewan' => 'required|exists:ras_hewan,idras',
            'idpemilik' => 'required|exists:pemilik,idpemilik',
        ]);

        Pet::create([
            'nama' => $request->nama_pet,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'warna_tanda' => $request->warna_tanda,
            'idras_hewan' => $request->idras_hewan,
            'idpemilik' => $request->idpemilik,
        ]);

        return redirect()->back()->with('success', 'Pet berhasil didaftarkan.');
    }
}

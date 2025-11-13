<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Dokter extends Model
{
    use HasFactory;

    protected $table = 'user'; // Assuming the doctors are stored in the users table
    protected $primaryKey = 'iduser';
    public $timestamps = false;

    /**
     * Get the role_user ID for the doctor
     * This method is used by the DokterController
     */
    public function getRoleUserIdDokter($iduser)
    {
        return DB::table('role_user as ru')
            ->join('role as r', 'ru.idrole', '=', 'r.idrole')
            ->where('ru.iduser', $iduser)
            ->where('r.nama_role', 'Dokter')
            ->value('ru.idrole_user');
    }

    /**
     * Get the role_user ID for the current doctor instance
     * This is an alias for getRoleUserIdDokter using the instance's iduser
     */
    public function getIdRoleUser()
    {
        return $this->getRoleUserIdDokter($this->iduser);
    }

    /**
     * Get examination schedule for the doctor
     * Only shows reservations with status 'Menunggu' (status=0) and not finished
     */
    public function getJadwalPemeriksaan($idrole_user)
    {
        return DB::table('temu_dokter as td')
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->join('pemilik as pe', 'p.idpemilik', '=', 'pe.idpemilik')
            ->join('user as u_pemilik', 'pe.iduser', '=', 'u_pemilik.iduser')
            ->where('td.idrole_user', $idrole_user)
            ->where('td.status', 0) // Status 0 = Menunggu/Aktif
            ->select(
                'td.idreservasi_dokter',
                'td.waktu_daftar',
                'p.nama as nama_pet',
                'p.jenis_kelamin',
                'p.tanggal_lahir',
                'u_pemilik.nama as nama_pemilik'
            )
            ->orderBy('td.waktu_daftar', 'asc')
            ->get();
    }

    /**
     * Get medical history records by doctor
     * Retrieves medical records for a specific doctor based on user ID
     */
    public function getRiwayatRekamMedisByDokter(int $iduser)
    {
        return DB::table('rekam_medis as rm')
            ->join('temu_dokter as td', 'rm.idreservasi_dokter', '=', 'td.idreservasi_dokter')
            ->join('pet as p', 'rm.idpet', '=', 'p.idpet')
            ->join('pemilik as pe', 'p.idpemilik', '=', 'pe.idpemilik')
            ->join('user as u', 'pe.iduser', '=', 'u.iduser')
            ->join('role_user as ru', 'rm.dokter_pemeriksa', '=', 'ru.idrole_user')
            ->where('ru.iduser', $iduser)
            ->select(
                'rm.idrekam_medis',
                'rm.created_at',
                'rm.diagnosa',
                'p.nama as nama_pet',
                'u.nama as nama_pemilik',
                'td.idreservasi_dokter'
            )
            ->orderBy('rm.created_at', 'desc')
            ->get();
    }
}

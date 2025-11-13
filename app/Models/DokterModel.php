<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DokterModel extends Model
{
    use HasFactory;

    public function getRiwayatRekamMedisByDokter(int $iduser): array
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
                'p.nama AS nama_pet',
                'u.nama AS nama_pemilik',
                'td.idreservasi_dokter'
            )
            ->orderBy('rm.created_at', 'desc')
            ->get()
            ->toArray();
    }
}

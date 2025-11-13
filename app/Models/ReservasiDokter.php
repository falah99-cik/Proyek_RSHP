<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReservasiDokter extends Model
{
    use HasFactory;

    protected $table = 'temu_dokter';
    protected $primaryKey = 'idreservasi_dokter';

    public function getRoleUserIdDokter($iduser)
    {
        return DB::table('role_user')
            ->where('iduser', $iduser)
            ->where('idrole', 2) // 2 is the role id for 'Dokter'
            ->value('idrole_user');
    }

    public function getPetIdByReservasi(int $idreservasi): ?int
    {
        return DB::table('temu_dokter')->where('idreservasi_dokter', $idreservasi)->value('idpet');
    }

    public function getPetAndOwnerDetails(int $idpet): ?array
    {
        return (array) DB::table('pet as p')
            ->join('pemilik as pem', 'p.idpemilik', '=', 'pem.idpemilik')
            ->join('user as u', 'pem.iduser', '=', 'u.iduser')
            ->join('ras_hewan as rh', 'p.idras_hewan', '=', 'rh.idras_hewan')
            ->join('jenis_hewan as jh', 'rh.idjenis_hewan', '=', 'jh.idjenis_hewan')
            ->where('p.idpet', $idpet)
            ->select(
                'p.idpet',
                'p.nama as nama_pet',
                'p.tanggal_lahir',
                'p.jenis_kelamin',
                'p.warna_tanda',
                'u.nama as nama_pemilik',
                'u.email',
                'pem.no_wa',
                'pem.alamat',
                'rh.nama_ras',
                'jh.nama_jenis_hewan'
            )
            ->first();
    }

    public function getRekamMedisHistory(int $idpet): array
    {
        return DB::table('rekam_medis as rm')
            ->join('role_user as ru', 'rm.dokter_pemeriksa', '=', 'ru.idrole_user')
            ->join('user as u', 'ru.iduser', '=', 'u.iduser')
            ->where('rm.idpet', $idpet)
            ->select('rm.idrekam_medis', 'rm.created_at', 'rm.anamnesa', 'rm.temuan_klinis', 'rm.diagnosa', 'u.nama as nama_dokter')
            ->orderBy('rm.created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function getReservasiDetail($idreservasi, $idrole_user_dokter)
    {
        return DB::table('temu_dokter as td')
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->join('pemilik as pe', 'p.idpemilik', '=', 'pe.idpemilik')
            ->join('user as u_pemilik', 'pe.iduser', '=', 'u_pemilik.iduser')
            ->join('ras_hewan as rh', 'p.idras_hewan', '=', 'rh.idras_hewan')
            ->join('jenis_hewan as jh', 'rh.idjenis_hewan', '=', 'jh.idjenis_hewan')
            ->where('td.idreservasi_dokter', $idreservasi)
            ->where('td.idrole_user', $idrole_user_dokter)
            ->select(
                'td.idreservasi_dokter',
                'td.status as status_reservasi',
                'p.idpet',
                'p.nama as nama_pet',
                'p.tanggal_lahir',
                'p.jenis_kelamin',
                'p.warna_tanda',
                'rh.nama_ras',
                'jh.nama_jenis_hewan',
                'u_pemilik.nama as nama_pemilik',
                'pe.alamat',
                'pe.no_wa'
            )
            ->first();
    }

    public function getRekamMedisDetails(int $idrekam_medis): array
    {
        return DB::table('detail_rekam_medis as drm')
            ->join('kode_tindakan_terapi as ktt', 'drm.idkode_tindakan_terapi', '=', 'ktt.idkode_tindakan_terapi')
            ->join('kategori as k', 'ktt.idkategori', '=', 'k.idkategori')
            ->join('kategori_klinis as kk', 'ktt.idkategori_klinis', '=', 'kk.idkategori_klinis')
            ->where('drm.idrekam_medis', $idrekam_medis)
            ->select('drm.iddetail_rekam_medis', 'ktt.deskripsi_tindakan_terapi', 'k.nama_kategori', 'kk.nama_kategori_klinis', 'drm.detail')
            ->get()
            ->toArray();
    }

    public function getAllReservasiByDokter(int $iduser): array
    {
        return DB::table('temu_dokter as td')
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->join('pemilik as pe', 'p.idpemilik', '=', 'pe.idpemilik')
            ->join('user as u', 'pe.iduser', '=', 'u.iduser')
            ->join('role_user as ru', 'td.idrole_user', '=', 'ru.idrole_user')
            ->leftJoin('rekam_medis as rm', 'rm.idreservasi_dokter', '=', 'td.idreservasi_dokter')
            ->where('ru.iduser', $iduser)
            ->select(
                'td.idreservasi_dokter',
                'td.status',
                'u.nama as nama_pemilik',
                'p.nama as nama_pet',
                'p.idpet',
                'td.waktu_daftar',
                'rm.created_at as waktu_selesai_rm'
            )
            ->orderBy('td.waktu_daftar', 'desc')
            ->get()
            ->toArray();
    }

    public function getReservasiByStatus(string $status): array
    {
        return DB::table('temu_dokter as td')
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->join('pemilik as pe', 'p.idpemilik', '=', 'pe.idpemilik')
            ->join('user as u', 'pe.iduser', '=', 'u.iduser')
            ->join('role_user as ru', 'td.idrole_user', '=', 'ru.idrole_user')
            ->join('user as u_dokter', 'ru.iduser', '=', 'u_dokter.iduser')
            ->where('td.status', $status)
            ->select(
                'td.idreservasi_dokter',
                'td.status',
                'td.waktu_daftar',
                'u.nama as nama_pemilik',
                'p.nama as nama_pet',
                'u_dokter.nama as nama_dokter'
            )
            ->orderBy('td.waktu_daftar', 'desc')
            ->get()
            ->toArray();
    }

    public function getReservasiStats(): array
    {
        $stats = DB::table('temu_dokter')
            ->select(
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "menunggu" THEN 1 ELSE 0 END) as menunggu'),
                DB::raw('SUM(CASE WHEN status = "diproses" THEN 1 ELSE 0 END) as diproses'),
                DB::raw('SUM(CASE WHEN status = "selesai" THEN 1 ELSE 0 END) as selesai'),
                DB::raw('SUM(CASE WHEN status = "dibatalkan" THEN 1 ELSE 0 END) as dibatalkan')
            )
            ->first();

        return [
            'total' => $stats->total ?? 0,
            'menunggu' => $stats->menunggu ?? 0,
            'diproses' => $stats->diproses ?? 0,
            'selesai' => $stats->selesai ?? 0,
            'dibatalkan' => $stats->dibatalkan ?? 0
        ];
    }

    public function searchReservasi(string $keyword): array
    {
        return DB::table('temu_dokter as td')
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->join('pemilik as pe', 'p.idpemilik', '=', 'pe.idpemilik')
            ->join('user as u', 'pe.iduser', '=', 'u.iduser')
            ->join('role_user as ru', 'td.idrole_user', '=', 'ru.idrole_user')
            ->join('user as u_dokter', 'ru.iduser', '=', 'u_dokter.iduser')
            ->where(function ($query) use ($keyword) {
                $query->where('p.nama', 'like', "%{$keyword}%")
                    ->orWhere('u.nama', 'like', "%{$keyword}%")
                    ->orWhere('u_dokter.nama', 'like', "%{$keyword}%");
            })
            ->select(
                'td.idreservasi_dokter',
                'td.status',
                'td.waktu_daftar',
                'u.nama as nama_pemilik',
                'p.nama as nama_pet',
                'u_dokter.nama as nama_dokter'
            )
            ->orderBy('td.waktu_daftar', 'desc')
            ->get()
            ->toArray();
    }

    public function getRecentReservasi(int $limit = 10): array
    {
        return DB::table('temu_dokter as td')
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->join('pemilik as pe', 'p.idpemilik', '=', 'pe.idpemilik')
            ->join('user as u', 'pe.iduser', '=', 'u.iduser')
            ->join('role_user as ru', 'td.idrole_user', '=', 'ru.idrole_user')
            ->join('user as u_dokter', 'ru.iduser', '=', 'u_dokter.iduser')
            ->select(
                'td.idreservasi_dokter',
                'td.status',
                'td.waktu_daftar',
                'u.nama as nama_pemilik',
                'p.nama as nama_pet',
                'u_dokter.nama as nama_dokter'
            )
            ->orderBy('td.waktu_daftar', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }
}

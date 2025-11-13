<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Temu extends Model
{
    use HasFactory;

    protected $table = 'temu_dokter';
    protected $primaryKey = 'idtemu_dokter';

    public function getListTemuToday($today)
    {
        return DB::table('temu_dokter as td')
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->join('pemilik as pe', 'p.idpemilik', '=', 'pe.idpemilik')
            ->join('user as u_pet', 'pe.iduser', '=', 'u_pet.iduser')
            ->join('role_user as ru_dokter', 'td.idrole_user', '=', 'ru_dokter.idrole_user')
            ->join('user as u_dokter', 'ru_dokter.iduser', '=', 'u_dokter.iduser')
            ->whereDate('td.waktu_daftar', $today)
            ->select(
                'td.no_urut',
                'td.waktu_daftar',
                'p.nama as nama_pet',
                'u_pet.nama as nama_pemilik',
                'u_dokter.nama as nama_dokter',
                'td.status'
            )
            ->orderBy('td.no_urut', 'asc')
            ->get();
    }

    public function getTemuDetailByNoUrut($no_urut, $today)
    {
        return DB::table('temu_dokter as td')
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->join('pemilik as pe', 'p.idpemilik', '=', 'pe.idpemilik')
            ->join('user as u_pemilik', 'pe.iduser', '=', 'u_pemilik.iduser')
            ->join('role_user as ru_dokter', 'td.idrole_user', '=', 'ru_dokter.idrole_user')
            ->join('user as u_dokter', 'ru_dokter.iduser', '=', 'u_dokter.iduser')
            ->where('td.no_urut', $no_urut)
            ->whereDate('td.waktu_daftar', $today)
            ->select(
                'td.idtemu_dokter',
                'td.no_urut',
                'td.waktu_daftar',
                'td.idpet',
                'p.nama as nama_pet',
                'p.tanggal_lahir as tgl_lahir_pet',
                'p.jenis_kelamin as jk_pet',
                'u_pemilik.nama as nama_pemilik',
                'u_pemilik.alamat as alamat_pemilik',
                'u_pemilik.telp as telp_pemilik',
                'u_dokter.nama as nama_dokter',
                'td.status'
            )
            ->first();
    }

    public function getTemuByDateRange($startDate, $endDate)
    {
        return DB::table('temu_dokter as td')
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->join('pemilik as pe', 'p.idpemilik', '=', 'pe.idpemilik')
            ->join('user as u_pet', 'pe.iduser', '=', 'u_pet.iduser')
            ->join('role_user as ru_dokter', 'td.idrole_user', '=', 'ru_dokter.idrole_user')
            ->join('user as u_dokter', 'ru_dokter.iduser', '=', 'u_dokter.iduser')
            ->whereBetween('td.waktu_daftar', [$startDate, $endDate])
            ->select(
                'td.idtemu_dokter',
                'td.no_urut',
                'td.waktu_daftar',
                'p.nama as nama_pet',
                'u_pet.nama as nama_pemilik',
                'u_dokter.nama as nama_dokter',
                'td.status'
            )
            ->orderBy('td.waktu_daftar', 'desc')
            ->get();
    }

    public function getTemuStatsByDate($date)
    {
        $stats = DB::table('temu_dokter')
            ->whereDate('waktu_daftar', $date)
            ->select(
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as menunggu'),
                DB::raw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as selesai'),
                DB::raw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as dibatalkan')
            )
            ->first();

        return [
            'total' => $stats->total ?? 0,
            'menunggu' => $stats->menunggu ?? 0,
            'selesai' => $stats->selesai ?? 0,
            'dibatalkan' => $stats->dibatalkan ?? 0
        ];
    }

    public function getNextAvailableNoUrut($today)
    {
        $lastNoUrut = DB::table('temu_dokter')
            ->whereDate('waktu_daftar', $today)
            ->max('no_urut');

        if ($lastNoUrut) {
            $numericPart = (int) substr($lastNoUrut, -3);
            $datePart = substr($lastNoUrut, 0, 8);
            $nextNumeric = $numericPart + 1;
            return $datePart . '-' . str_pad($nextNumeric, 3, '0', STR_PAD_LEFT);
        }

        $datePart = date('Ymd', strtotime($today));
        return $datePart . '-001';
    }

    public function searchTemu($keyword, $date = null)
    {
        $query = DB::table('temu_dokter as td')
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->join('pemilik as pe', 'p.idpemilik', '=', 'pe.idpemilik')
            ->join('user as u_pet', 'pe.iduser', '=', 'u_pet.iduser')
            ->join('role_user as ru_dokter', 'td.idrole_user', '=', 'ru_dokter.idrole_user')
            ->join('user as u_dokter', 'ru_dokter.iduser', '=', 'u_dokter.iduser')
            ->where(function ($q) use ($keyword) {
                $q->where('p.nama', 'like', "%{$keyword}%")
                    ->orWhere('u_pet.nama', 'like', "%{$keyword}%")
                    ->orWhere('u_dokter.nama', 'like', "%{$keyword}%");
            })
            ->select(
                'td.idtemu_dokter',
                'td.no_urut',
                'td.waktu_daftar',
                'p.nama as nama_pet',
                'u_pet.nama as nama_pemilik',
                'u_dokter.nama as nama_dokter',
                'td.status'
            )
            ->orderBy('td.waktu_daftar', 'desc');

        if ($date) {
            $query->whereDate('td.waktu_daftar', $date);
        }

        return $query->get();
    }

    public function getTemuByDokter($idrole_user, $date = null)
    {
        $query = DB::table('temu_dokter as td')
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->join('pemilik as pe', 'p.idpemilik', '=', 'pe.idpemilik')
            ->join('user as u_pet', 'pe.iduser', '=', 'u_pet.iduser')
            ->join('role_user as ru_dokter', 'td.idrole_user', '=', 'ru_dokter.idrole_user')
            ->join('user as u_dokter', 'ru_dokter.iduser', '=', 'u_dokter.iduser')
            ->where('td.idrole_user', $idrole_user)
            ->select(
                'td.idtemu_dokter',
                'td.no_urut',
                'td.waktu_daftar',
                'p.nama as nama_pet',
                'u_pet.nama as nama_pemilik',
                'u_dokter.nama as nama_dokter',
                'td.status'
            )
            ->orderBy('td.waktu_daftar', 'desc');

        if ($date) {
            $query->whereDate('td.waktu_daftar', $date);
        }

        return $query->get();
    }
}

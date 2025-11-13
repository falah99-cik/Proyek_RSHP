<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TemuDokter extends Model
{
    use HasFactory;

    protected $table = 'temu_dokter';
    protected $primaryKey = 'idtemu';
    protected $fillable = [
        'no_urut',
        'waktu_daftar',
        'tanggal_temu',
        'status',
        'idpet',
        'idrole_user',
    ];
    const CREATED_AT = 'waktu_daftar';
    const UPDATED_AT = null;

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'idpet', 'idpet');
    }

    public function dokter()
    {
        return $this->belongsTo(User::class, 'idrole_user', 'iduser');
    }

    public function pemilik()
    {
        return $this->hasOneThrough(Pemilik::class, Pet::class, 'idpet', 'idpemilik', 'idpet', 'idpemilik');
    }

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
            ->get()
            ->toArray();
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
                'td.idtemu',
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
}

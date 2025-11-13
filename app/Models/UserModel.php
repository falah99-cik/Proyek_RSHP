<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserModel extends Model
{
    use HasFactory;

    public function getAllDokters()
    {
        return DB::table('role_user as ru')
            ->join('user as u', 'ru.iduser', '=', 'u.iduser')
            ->where('ru.idrole', 2) // idrole 2 adalah untuk Dokter
            ->select('ru.idrole_user', 'u.nama')
            ->orderBy('u.nama', 'asc')
            ->get();
    }

    public function getAllUsersByRole($roleId)
    {
        return DB::table('role_user as ru')
            ->join('user as u', 'ru.iduser', '=', 'u.iduser')
            ->where('ru.idrole', $roleId)
            ->select('ru.idrole_user', 'u.nama', 'u.email')
            ->orderBy('u.nama', 'asc')
            ->get();
    }

    public function getUserRoleInfo($iduser)
    {
        return DB::table('role_user as ru')
            ->join('role as r', 'ru.idrole', '=', 'r.idrole')
            ->join('user as u', 'ru.iduser', '=', 'u.iduser')
            ->where('ru.iduser', $iduser)
            ->select('ru.idrole_user', 'r.nama_role', 'u.nama', 'u.email', 'ru.status')
            ->first();
    }

    public function getAllRoles()
    {
        return DB::table('role')
            ->select('idrole', 'nama_role')
            ->orderBy('nama_role', 'asc')
            ->get();
    }

    public function searchUsersByName($keyword)
    {
        return DB::table('user')
            ->where('nama', 'like', "%$keyword%")
            ->select('iduser', 'nama', 'email')
            ->orderBy('nama', 'asc')
            ->get();
    }

    public function getActiveUsersByRole($roleId)
    {
        return DB::table('role_user as ru')
            ->join('user as u', 'ru.iduser', '=', 'u.iduser')
            ->where('ru.idrole', $roleId)
            ->where('ru.status', 1) // Active status
            ->select('ru.idrole_user', 'u.nama', 'u.email')
            ->orderBy('u.nama', 'asc')
            ->get();
    }

    public function getUserCountByRole()
    {
        return DB::table('role_user as ru')
            ->join('role as r', 'ru.idrole', '=', 'r.idrole')
            ->select('r.nama_role', DB::raw('COUNT(*) as total_users'))
            ->groupBy('r.nama_role')
            ->orderBy('total_users', 'desc')
            ->get();
    }

    public function getDokterWithJadwalCount()
    {
        return DB::table('role_user as ru')
            ->join('user as u', 'ru.iduser', '=', 'u.iduser')
            ->leftJoin('temu_dokter as td', 'ru.idrole_user', '=', 'td.idrole_user')
            ->where('ru.idrole', 2) // Dokter role
            ->select(
                'ru.idrole_user',
                'u.nama',
                DB::raw('COUNT(td.idreservasi_dokter) as total_jadwal')
            )
            ->groupBy('ru.idrole_user', 'u.nama')
            ->orderBy('u.nama', 'asc')
            ->get();
    }
}

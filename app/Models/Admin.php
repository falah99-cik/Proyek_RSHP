<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Admin extends Model
{
    use HasFactory;

    public function getTotalUsers()
    {
        return DB::table('user')->count();
    }

    public function getTotalPets()
    {
        return DB::table('pet')->count();
    }

    public function getTotalRekamMedis()
    {
        return DB::table('rekam_medis')->count();
    }

    public function getTotalRecentRekamMedis()
    {
        return DB::table('rekam_medis')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();
    }

    public function getPetSpeciesData()
    {
        return DB::table('pet as p')
            ->join('ras_hewan as rh', 'p.idras_hewan', '=', 'rh.idras_hewan')
            ->join('jenis_hewan as j', 'rh.idjenis_hewan', '=', 'j.idjenis_hewan')
            ->select('j.nama_jenis_hewan', DB::raw('COUNT(p.idpet) as total_hewan'))
            ->groupBy('j.nama_jenis_hewan')
            ->orderBy('total_hewan', 'desc')
            ->get()
            ->toArray(); // ← FIX PENTING
    }

    public function getRekamMedisMonthly()
    {
        return DB::table('rekam_medis')
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as bulan"),
                DB::raw('COUNT(idrekam_medis) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->get()
            ->toArray();
    }

    public function getRecentRekamMedisActivities($limit = 10)
    {
        return DB::table('rekam_medis as rm')
            ->join('user as u', 'rm.dokter_pemeriksa', '=', 'u.iduser')
            ->join('pet as p', 'rm.idpet', '=', 'p.idpet')
            ->select('rm.created_at', 'u.nama as nama_dokter', 'p.nama', 'rm.diagnosa as detail')
            ->orderBy('rm.created_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray(); // optional tapi bagus
    }
}

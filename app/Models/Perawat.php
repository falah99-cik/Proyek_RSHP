<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Perawat extends Model
{
    use HasFactory;

    protected $table = 'perawat';
    protected $primaryKey = 'idperawat';
    public $timestamps = false;

    protected $fillable = ['iduser', 'no_wa', 'alamat'];

    /**
     * Get the user associated with the nurse.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'iduser');
    }

    /**
     * Get the nurse ID based on the user ID.
     * 
     * @return int|null The nurse ID or null if not found
     */
    public function getIdPerawat()
    {
        return DB::table('perawat')
            ->where('iduser', $this->iduser)
            ->value('idperawat');
    }

    /**
     * Get the list of patients with their last medical record update.
     * This method retrieves all pets that have medical records, along with their owner information
     * and the timestamp of their most recent medical record.
     * 
     * @return array List of patients with their information and last update time
     */
    public function getDaftarPasienRekamMedis(): array
    {
        return DB::table('rekam_medis as rm')
            ->join('pet as p', 'rm.idpet', '=', 'p.idpet')
            ->join('pemilik as pe', 'p.idpemilik', '=', 'pe.idpemilik')
            ->join('user as u', 'pe.iduser', '=', 'u.iduser')
            ->select('p.idpet', 'p.nama as nama_pet', 'u.nama as nama_pemilik', DB::raw('MAX(rm.created_at) as last_updated'))
            ->groupBy('p.idpet', 'p.nama', 'u.nama')
            ->orderBy('last_updated', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * Get medical records assigned to this nurse.
     * 
     * @return \Illuminate\Support\Collection Collection of medical records
     */
    public function getRekamMedisByPerawat()
    {
        return DB::table('detail_rekam_medis as drm')
            ->join('rekam_medis as rm', 'drm.idrekam_medis', '=', 'rm.idrekam_medis')
            ->join('pet as p', 'rm.idpet', '=', 'p.idpet')
            ->join('pemilik as pe', 'p.idpemilik', '=', 'pe.idpemilik')
            ->join('user as u_pemilik', 'pe.iduser', '=', 'u_pemilik.iduser')
            ->where('drm.idperawat', $this->idperawat)
            ->select(
                'rm.idrekam_medis',
                'rm.created_at',
                'rm.diagnosa',
                'p.nama as nama_pet',
                'u_pemilik.nama as nama_pemilik',
                'drm.created_at as tindakan_created_at'
            )
            ->orderBy('drm.created_at', 'desc')
            ->get();
    }

    /**
     * Get statistics for this nurse.
     * 
     * @return array Statistics array with total records and today's records
     */
    public function getStatistikPerawat()
    {
        $totalRekamMedis = DB::table('detail_rekam_medis')
            ->where('idperawat', $this->idperawat)
            ->count();

        $rekamMedisHariIni = DB::table('detail_rekam_medis')
            ->where('idperawat', $this->idperawat)
            ->whereDate('created_at', today())
            ->count();

        return [
            'total_rekam_medis' => $totalRekamMedis,
            'rekam_medis_hari_ini' => $rekamMedisHariIni,
        ];
    }
}

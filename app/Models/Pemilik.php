<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pemilik extends Model
{
    use HasFactory;

    protected $table = 'pemilik';
    protected $primaryKey = 'idpemilik';
    public $timestamps = false;

    protected $fillable = ['iduser', 'no_wa', 'alamat'];

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser');
    }

    public function pets()
    {
        return $this->hasMany(Pet::class, 'idpemilik');
    }

    public function getUserProfileData()
    {
        return DB::table('user as u')
            ->leftJoin('pemilik as pe', 'u.iduser', '=', 'pe.iduser')
            ->where('u.iduser', $this->iduser)
            ->select('u.nama', 'u.email', 'pe.no_wa', 'pe.alamat')
            ->first();
    }

    public function getDaftarPet($idpemilik)
    {
        return DB::table('pet as p')
            ->join('ras_hewan as rh', 'p.idras_hewan', '=', 'rh.idras_hewan')
            ->join('jenis_hewan as jh', 'rh.idjenis_hewan', '=', 'jh.idjenis_hewan')
            ->where('p.idpemilik', $idpemilik)
            ->select('p.idpet', 'p.nama as nama_pet', 'p.tanggal_lahir', 'p.jenis_kelamin', 'rh.nama_ras', 'jh.nama_jenis_hewan')
            ->orderBy('p.idpet', 'desc')
            ->get();
    }

    public function getDaftarReservasi($idpemilik)
    {
        return DB::table('temu_dokter as td')
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->leftJoin('role_user as ru_dokter', 'td.idrole_user', '=', 'ru_dokter.idrole_user')
            ->leftJoin('user as u_dokter', 'ru_dokter.iduser', '=', 'u_dokter.iduser')
            ->leftJoin('rekam_medis as rm', 'td.idreservasi_dokter', '=', 'rm.idreservasi_dokter')
            ->where('p.idpemilik', $idpemilik)
            ->select('td.idreservasi_dokter', 'td.waktu_daftar', 'td.status', 'p.nama as nama_pet', 'u_dokter.nama as nama_dokter', 'rm.idrekam_medis')
            ->orderBy('td.waktu_daftar', 'desc')
            ->get();
    }

    public function getDetailRekamMedis($idrekam_medis, $idpemilik)
    {
        $data_rm = DB::table('rekam_medis as rm')
            ->join('detail_rekam_medis as dr', 'rm.idrekam_medis', '=', 'dr.idrekam_medis')
            ->join('kode_tindakan_terapi as ktt', 'dr.idkode_tindakan_terapi', '=', 'ktt.idkode_tindakan_terapi')
            ->join('pet as p', 'rm.idpet', '=', 'p.idpet')
            ->join('pemilik as pe', 'p.idpemilik', '=', 'pe.idpemilik')
            ->leftJoin('role_user as ru_dokter', 'rm.dokter_pemeriksa', '=', 'ru_dokter.idrole_user')
            ->leftJoin('user as u_dokter', 'ru_dokter.iduser', '=', 'u_dokter.iduser')
            ->where('rm.idrekam_medis', $idrekam_medis)
            ->where('p.idpemilik', $idpemilik)
            ->select('rm.idrekam_medis', 'rm.created_at', 'rm.anamnesa', 'rm.temuan_klinis', 'rm.diagnosa', 'p.nama as nama_pet', 'p.tanggal_lahir', 'p.jenis_kelamin', 'u_dokter.nama as nama_dokter')
            ->first();

        if (!$data_rm) {
            return null;
        }

        $detail_tindakan = DB::table('detail_rekam_medis as drm')
            ->join('kode_tindakan_terapi as ktt', 'drm.idkode_tindakan_terapi', '=', 'ktt.idkode_tindakan_terapi')
            ->where('drm.idrekam_medis', $idrekam_medis)
            ->select('ktt.deskripsi_tindakan_terapi', 'drm.detail')
            ->get();

        $data_rm->tindakan = $detail_tindakan;

        return $data_rm;
    }

    public function getDaftarSemuaRekamMedis($idpemilik)
    {
        return DB::table('rekam_medis as rm')
            ->join('pet as p', 'rm.idpet', '=', 'p.idpet')
            ->leftJoin('role_user as ru_dokter', 'rm.dokter_pemeriksa', '=', 'ru_dokter.idrole_user')
            ->leftJoin('user as u_dokter', 'ru_dokter.iduser', '=', 'u_dokter.iduser')
            ->where('p.idpemilik', $idpemilik)
            ->select('rm.idrekam_medis', 'rm.created_at', 'rm.diagnosa', 'p.nama as nama_pet', 'u_dokter.nama as nama_dokter')
            ->orderBy('rm.created_at', 'desc')
            ->get();
    }

    public function updateUserProfile($data)
    {
        return DB::table('pemilik')
            ->where('iduser', $this->iduser)
            ->update($data);
    }

    /**
     * Get the owner ID (idpemilik) based on the logged-in user ID.
     * 
     * @return int|null The owner ID or null if not found
     */
    public function getIdPemilik()
    {
        return DB::table('pemilik')
            ->where('iduser', $this->iduser)
            ->value('idpemilik');
    }

    /**
     * Register a new owner (pemilik) with user account and role assignment
     * This method handles the complete registration process including:
     * - Creating user account
     * - Assigning owner role
     * - Creating owner profile
     * 
     * @param array $data Registration data containing nama, email, password, no_wa, alamat
     * @return array Status array with success/error message
     */
    public static function registerOwner($data)
    {
        return DB::transaction(function () use ($data) {
            try {
                // Create user account
                $user = User::create([
                    'nama' => $data['nama'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']), // Hash password using Laravel's bcrypt
                ]);

                // Assign owner role (ROLE_ID_PEMILIK = 5)
                $ROLE_ID_PEMILIK = 5;
                DB::table('role_user')->insert([
                    'iduser' => $user->iduser,
                    'idrole' => $ROLE_ID_PEMILIK,
                ]);

                // Create owner profile
                $pemilik = self::create([
                    'iduser' => $user->iduser,
                    'no_wa' => $data['no_wa'],
                    'alamat' => $data['alamat'],
                ]);

                return [
                    'status' => 'success',
                    'message' => 'Pemilik berhasil didaftarkan.',
                    'user' => $user,
                    'pemilik' => $pemilik
                ];
            } catch (\Exception $e) {
                // Handle duplicate entry errors
                if (str_contains($e->getMessage(), 'Duplicate entry')) {
                    return [
                        'status' => 'error',
                        'message' => 'Registrasi gagal: Email (atau data unik) sudah terdaftar.'
                    ];
                }

                return [
                    'status' => 'error',
                    'message' => 'Registrasi gagal: ' . $e->getMessage()
                ];
            }
        });
    }
}

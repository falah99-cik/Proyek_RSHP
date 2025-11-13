<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RekamMedis extends Model
{
    use HasFactory;

    protected $table = 'rekam_medis';
    protected $primaryKey = 'idrekam_medis';

    protected $fillable = [
        'idpet',
        'dokter_pemeriksa',
        'anamnesa',
        'temuan_klinis',
        'diagnosa',
        'tindakan_pengobatan'
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'idpet');
    }

    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_pemeriksa', 'iduser');
    }

    public function pemilik()
    {
        return $this->hasOneThrough(Pemilik::class, Pet::class, 'idpet', 'idpemilik', 'idpet', 'idpemilik');
    }

    public function detailRekamMedis()
    {
        return $this->hasMany(DetailRekamMedis::class, 'idrekam_medis');
    }

    public static function getIdRoleUserDokter($iduser)
    {
        return DB::table('role_user')
            ->where('iduser', $iduser)
            ->where('idrole', 2) // 2 is the role id for 'Dokter'
            ->value('idrole_user');
    }

    public static function getReservasiDetailForPeriksa($idreservasi, $idrole_user_dokter)
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

    public static function getAllKodeTindakanTerapi()
    {
        return DB::table('kode_tindakan_terapi as ktt')
            ->join('kategori_klinis as kk', 'ktt.idkategori_klinis', '=', 'kk.idkategori_klinis')
            ->join('kategori as kt', 'ktt.idkategori', '=', 'kt.idkategori')
            ->select('ktt.idkode_tindakan_terapi', 'ktt.kode', 'ktt.deskripsi_tindakan_terapi', 'kk.nama_kategori_klinis', 'kt.nama_kategori')
            ->orderBy('kk.idkategori_klinis')
            ->orderBy('kt.nama_kategori')
            ->orderBy('ktt.kode')
            ->get();
    }

    public static function saveRekamMedisAndCloseReservasi($data)
    {
        DB::beginTransaction();
        try {
            $rekamMedis = self::create($data);

            if (!empty($data['tindakan_terapi_codes'])) {
                foreach ($data['tindakan_terapi_codes'] as $idkode) {
                    DB::table('detail_rekam_medis')->insert([
                        'idrekam_medis' => $rekamMedis->idrekam_medis,
                        'idkode_tindakan_terapi' => $idkode
                    ]);
                }
            }

            DB::table('temu_dokter')
                ->where('idreservasi_dokter', $data['idreservasi'])
                ->update(['status' => 1]);

            DB::commit();
            return ['status' => 'success', 'message' => 'Rekam medis berhasil disimpan dan reservasi ditutup.'];
        } catch (\Exception $e) {
            DB::rollBack();
            error_log("Rekam Medis Transaction Failed: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Gagal menyimpan rekam medis. Error: ' . $e->getMessage()];
        }
    }

    public static function getPetDetail($pet_id)
    {
        return DB::table('pet as p')
            ->join('ras_hewan as rh', 'p.idras_hewan', '=', 'rh.idras_hewan')
            ->join('jenis_hewan as jh', 'rh.idjenis_hewan', '=', 'jh.idjenis_hewan')
            ->join('pemilik as pe', 'p.idpemilik', '=', 'pe.idpemilik')
            ->join('user as u_pemilik', 'pe.iduser', '=', 'u_pemilik.iduser')
            ->where('p.idpet', $pet_id)
            ->select(
                'p.idpet',
                'p.nama as nama_pet',
                'p.tanggal_lahir',
                'p.jenis_kelamin',
                'p.warna_tanda',
                'rh.nama_ras',
                'jh.nama_jenis_hewan',
                'u_pemilik.nama as nama_pemilik',
                'pe.no_wa',
                'u_pemilik.email',
                'pe.alamat'
            )
            ->first();
    }

    public static function getRekamMedisByPetId($pet_id)
    {
        return self::from('rekam_medis as rm')
            ->leftJoin('role_user as ru', 'rm.dokter_pemeriksa', '=', 'ru.idrole_user')
            ->leftJoin('user as u_dokter', 'ru.iduser', '=', 'u_dokter.iduser')
            ->where('rm.idpet', $pet_id)
            ->select('rm.*', 'u_dokter.nama as nama_dokter')
            ->orderBy('rm.created_at', 'desc')
            ->get();
    }

    public static function getRekamMedisByDokter($dokter_id)
    {
        return self::from('rekam_medis as rm')
            ->join('pet as p', 'rm.idpet', '=', 'p.idpet')
            ->join('pemilik as pe', 'p.idpemilik', '=', 'pe.idpemilik')
            ->join('user as u_pemilik', 'pe.iduser', '=', 'u_pemilik.iduser')
            ->where('rm.dokter_pemeriksa', $dokter_id)
            ->select('rm.*', 'p.nama as nama_pet', 'u_pemilik.nama as nama_pemilik')
            ->orderBy('rm.created_at', 'desc')
            ->get();
    }

    public static function getRekamMedisStats()
    {
        return [
            'total' => self::count(),
            'this_month' => self::whereMonth('created_at', now()->month)->count(),
            'this_week' => self::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'today' => self::whereDate('created_at', today())->count(),
            'by_dokter' => self::from('rekam_medis as rm')
                ->join('role_user as ru', 'rm.dokter_pemeriksa', '=', 'ru.idrole_user')
                ->join('user as u', 'ru.iduser', '=', 'u.iduser')
                ->select('u.nama as nama_dokter', DB::raw('COUNT(rm.idrekam_medis) as total'))
                ->groupBy('u.nama')
                ->orderBy('total', 'desc')
                ->get(),
        ];
    }

    public static function searchRekamMedis($keyword)
    {
        return self::from('rekam_medis as rm')
            ->join('pet as p', 'rm.idpet', '=', 'p.idpet')
            ->join('pemilik as pe', 'p.idpemilik', '=', 'pe.idpemilik')
            ->join('user as u_pemilik', 'pe.iduser', '=', 'u_pemilik.iduser')
            ->join('role_user as ru', 'rm.dokter_pemeriksa', '=', 'ru.idrole_user')
            ->join('user as u_dokter', 'ru.iduser', '=', 'u_dokter.iduser')
            ->where(function ($query) use ($keyword) {
                $query->where('p.nama', 'like', '%' . $keyword . '%')
                    ->orWhere('u_pemilik.nama', 'like', '%' . $keyword . '%')
                    ->orWhere('u_dokter.nama', 'like', '%' . $keyword . '%')
                    ->orWhere('rm.diagnosa', 'like', '%' . $keyword . '%')
                    ->orWhere('rm.tindakan_pengobatan', 'like', '%' . $keyword . '%');
            })
            ->select('rm.*', 'p.nama as nama_pet', 'u_pemilik.nama as nama_pemilik', 'u_dokter.nama as nama_dokter')
            ->orderBy('rm.created_at', 'desc')
            ->get();
    }

    public static function getRecentRekamMedis($limit = 10)
    {
        return self::from('rekam_medis as rm')
            ->join('pet as p', 'rm.idpet', '=', 'p.idpet')
            ->join('pemilik as pe', 'p.idpemilik', '=', 'pe.idpemilik')
            ->join('user as u_pemilik', 'pe.iduser', '=', 'u_pemilik.iduser')
            ->join('role_user as ru', 'rm.dokter_pemeriksa', '=', 'ru.idrole_user')
            ->join('user as u_dokter', 'ru.iduser', '=', 'u_dokter.iduser')
            ->select('rm.*', 'p.nama as nama_pet', 'u_pemilik.nama as nama_pemilik', 'u_dokter.nama as nama_dokter')
            ->orderBy('rm.created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getDetailWithRelationships()
    {
        return self::with(['pet.pemilik.user', 'pet.rasHewan.jenisHewan', 'dokter', 'detailRekamMedis.kodeTindakanTerapi'])
            ->where('idrekam_medis', $this->idrekam_medis)
            ->first();
    }
}

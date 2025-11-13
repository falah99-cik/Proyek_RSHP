<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pet extends Model
{
    use HasFactory;

    protected $table = 'pet';
    protected $primaryKey = 'idpet';
    public $timestamps = false;

    protected $fillable = [
        'idpemilik',
        'idjenis_hewan',
        'idras_hewan',
        'nama',
        'tanggal_lahir',
        'jenis_kelamin',
        'warna_tanda'
    ];

    public function owner()
    {
        return $this->belongsTo(Pemilik::class, 'idpemilik');
    }

    public function pemilik()
    {
        return $this->belongsTo(Pemilik::class, 'idpemilik');
    }

    public function jenisHewan()
    {
        return $this->belongsTo(JenisHewan::class, 'idjenis_hewan');
    }

    public function rasHewan()
    {
        return $this->belongsTo(RasHewan::class, 'idras_hewan');
    }

    public function rekamMedis()
    {
        return $this->hasMany(RekamMedis::class, 'idpet');
    }

    public static function getAllPetsWithDetails()
    {
        return DB::table('pet as p')
            ->join('pemilik as pe', 'p.idpemilik', '=', 'pe.idpemilik')
            ->join('user as u', 'pe.iduser', '=', 'u.iduser')
            ->join('ras_hewan as rh', 'p.idras_hewan', '=', 'rh.idras_hewan')
            ->join('jenis_hewan as jh', 'rh.idjenis_hewan', '=', 'jh.idjenis_hewan')
            ->select('p.idpet', 'p.nama', 'p.jenis_kelamin', 'p.tanggal_lahir', 'u.nama as nama_pemilik', 'jh.nama_jenis_hewan', 'rh.nama_ras')
            ->orderBy('p.nama', 'asc')
            ->get();
    }

    public static function registerPet($data)
    {
        try {
            self::create($data);
            return ['status' => 'success', 'message' => 'Pet **' . htmlspecialchars($data['nama']) . '** berhasil didaftarkan!'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Registrasi Pet gagal: ' . $e->getMessage()];
        }
    }

    public static function getAllPetsForTemu()
    {
        return DB::table('pet as p')
            ->join('pemilik as pe', 'p.idpemilik', '=', 'pe.idpemilik')
            ->join('user as u', 'pe.iduser', '=', 'u.iduser')
            ->select('p.idpet', 'p.nama', 'p.tanggal_lahir', 'u.nama as nama_pemilik')
            ->orderBy('p.nama', 'asc')
            ->get();
    }

    public static function getPetsByOwner($idpemilik)
    {
        return self::where('idpemilik', $idpemilik)
            ->with(['rasHewan.jenisHewan'])
            ->orderBy('nama', 'asc')
            ->get();
    }

    public static function getPetStats()
    {
        return [
            'total' => self::count(),
            'by_gender' => [
                'male' => self::where('jenis_kelamin', 'Jantan')->count(),
                'female' => self::where('jenis_kelamin', 'Betina')->count(),
            ],
            'by_species' => DB::table('pet as p')
                ->join('ras_hewan as rh', 'p.idras_hewan', '=', 'rh.idras_hewan')
                ->join('jenis_hewan as jh', 'rh.idjenis_hewan', '=', 'jh.idjenis_hewan')
                ->select('jh.nama_jenis_hewan', DB::raw('COUNT(p.idpet) as total'))
                ->groupBy('jh.nama_jenis_hewan')
                ->orderBy('total', 'desc')
                ->get(),
        ];
    }

    public static function searchPets($keyword)
    {
        return DB::table('pet as p')
            ->join('pemilik as pe', 'p.idpemilik', '=', 'pe.idpemilik')
            ->join('user as u', 'pe.iduser', '=', 'u.iduser')
            ->join('ras_hewan as rh', 'p.idras_hewan', '=', 'rh.idras_hewan')
            ->join('jenis_hewan as jh', 'rh.idjenis_hewan', '=', 'jh.idjenis_hewan')
            ->where(function ($query) use ($keyword) {
                $query->where('p.nama', 'like', '%' . $keyword . '%')
                    ->orWhere('u.nama', 'like', '%' . $keyword . '%')
                    ->orWhere('jh.nama_jenis_hewan', 'like', '%' . $keyword . '%')
                    ->orWhere('rh.nama_ras', 'like', '%' . $keyword . '%');
            })
            ->select('p.idpet', 'p.nama', 'p.jenis_kelamin', 'p.tanggal_lahir', 'u.nama as nama_pemilik', 'jh.nama_jenis_hewan', 'rh.nama_ras')
            ->orderBy('p.nama', 'asc')
            ->get();
    }

    public function getAge()
    {
        if (!$this->tanggal_lahir) {
            return null;
        }

        $birthDate = new \DateTime($this->tanggal_lahir);
        $currentDate = new \DateTime();
        $age = $birthDate->diff($currentDate);

        return [
            'years' => $age->y,
            'months' => $age->m,
            'days' => $age->d,
            'formatted' => $age->format('%y tahun %m bulan %d hari')
        ];
    }
}

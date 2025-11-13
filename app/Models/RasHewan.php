<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RasHewan extends Model
{
    use HasFactory;

    protected $table = 'ras_hewan';
    protected $primaryKey = 'idras_hewan';
    public $timestamps = false;

    protected $fillable = ['nama_ras', 'idjenis_hewan'];

    public function jenisHewan()
    {
        return $this->belongsTo(JenisHewan::class, 'idjenis_hewan');
    }

    public function pets()
    {
        return $this->hasMany(Pet::class, 'idras_hewan');
    }

    public static function readGroupedByJenis()
    {
        return DB::table('jenis_hewan as jh')
            ->leftJoin('ras_hewan as rh', 'jh.idjenis_hewan', '=', 'rh.idjenis_hewan')
            ->select('jh.idjenis_hewan', 'jh.nama_jenis_hewan', DB::raw("GROUP_CONCAT(rh.nama_ras, '_ID_', rh.idras_hewan SEPARATOR '|||') as grouped_ras"))
            ->groupBy('jh.idjenis_hewan', 'jh.nama_jenis_hewan')
            ->orderBy('jh.nama_jenis_hewan', 'asc')
            ->get();
    }

    public static function create(array $attributes = [])
    {
        if (self::where('nama_ras', $attributes['nama_ras'])->where('idjenis_hewan', $attributes['idjenis_hewan'])->exists()) {
            return ['status' => 'error', 'message' => 'Ras dengan nama tersebut sudah ada untuk jenis hewan ini.'];
        }

        parent::create($attributes);

        return ['status' => 'success', 'message' => 'Ras hewan berhasil ditambahkan!'];
    }

    public static function updateRasHewan(array $values, $id)
    {
        if (self::where('nama_ras', $values['nama_ras'])->where('idjenis_hewan', $values['idjenis_hewan'])->where('idras_hewan', '!=', $id)->exists()) {
            return ['status' => 'error', 'message' => 'Ras dengan nama tersebut sudah ada untuk jenis hewan yang dipilih.'];
        }

        $rasHewan = self::find($id);
        if ($rasHewan) {
            $rasHewan->update($values);
            return ['status' => 'success', 'message' => 'Ras hewan berhasil diperbarui!'];
        }

        return ['status' => 'error', 'message' => 'Ras hewan tidak ditemukan.'];
    }

    public static function getAllJenisHewan()
    {
        return JenisHewan::orderBy('nama_jenis_hewan', 'asc')->get();
    }
}

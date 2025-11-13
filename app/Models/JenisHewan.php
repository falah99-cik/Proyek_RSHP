<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisHewan extends Model
{
    use HasFactory;

    protected $table = 'jenis_hewan';
    protected $primaryKey = 'idjenis_hewan';
    public $timestamps = false;

    protected $fillable = ['nama_jenis_hewan'];

    public function rasHewan()
    {
        return $this->hasMany(RasHewan::class, 'idjenis_hewan');
    }

    public static function create(array $attributes = [])
    {
        if (self::where('nama_jenis_hewan', $attributes['nama_jenis_hewan'])->exists()) {
            return ['status' => 'error', 'message' => 'Jenis hewan dengan nama tersebut sudah ada.'];
        }

        parent::create($attributes);

        return ['status' => 'success', 'message' => 'Jenis hewan berhasil ditambahkan!'];
    }

    public static function updateJenisHewan(array $values, $id)
    {
        if (self::where('nama_jenis_hewan', $values['nama_jenis_hewan'])->where('idjenis_hewan', '!=', $id)->exists()) {
            return ['status' => 'error', 'message' => 'Jenis hewan dengan nama tersebut sudah ada.'];
        }

        $jenisHewan = self::find($id);
        if ($jenisHewan) {
            $jenisHewan->update($values);
            return ['status' => 'success', 'message' => 'Jenis hewan berhasil diperbarui!'];
        }

        return ['status' => 'error', 'message' => 'Jenis hewan tidak ditemukan.'];
    }

    public static function deleteJenisHewan($id)
    {
        $jenisHewan = self::find($id);
        if (!$jenisHewan) {
            return ['status' => 'error', 'message' => 'Jenis hewan tidak ditemukan.'];
        }

        try {
            $jenisHewan->delete();
            return ['status' => 'success', 'message' => 'Jenis hewan berhasil dihapus.'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Gagal menghapus jenis hewan: ' . $e->getMessage()];
        }
    }
}

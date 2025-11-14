<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pet extends Model
{
    use HasFactory;

    protected $table = 'pet';
    protected $primaryKey = 'idpet';

    public $timestamps = false;

    protected $fillable = ['nama', 'idpemilik', 'idras_hewan', 'tanggal_lahir', 'jenis_kelamin'];

    public function pemilik()
    {
        return $this->belongsTo(Pemilik::class, 'idpemilik', 'idpemilik');
    }

    public function ras()
    {
        return $this->belongsTo(RasHewan::class, 'idras_hewan', 'idras_hewan');
    }

    public static function getAllPetsWithDetails()
    {
        return self::select('pet.*')
            ->with(['jenisHewan', 'pemilik'])
            ->orderBy('idpet', 'DESC')
            ->get();
    }
}

<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Owner extends Model
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

    public static function registerOwner($data)
    {
        DB::beginTransaction();

        try {
            $user = User::create([
                'nama' => $data['nama'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $user->roles()->attach(5); // 5 is the role id for 'Pemilik'

            self::create([
                'iduser' => $user->iduser,
                'no_wa' => $data['no_wa'],
                'alamat' => $data['alamat'],
            ]);

            DB::commit();
            return ['status' => 'success', 'message' => 'Pemilik berhasil didaftarkan.'];
        } catch (Exception $e) {
            DB::rollBack();

            $errorMessage = $e->getMessage();
            if (strpos($errorMessage, 'Duplicate entry') !== false) {
                return ['status' => 'error', 'message' => 'Registrasi gagal: Email (atau data unik) sudah terdaftar.'];
            }

            return ['status' => 'error', 'message' => 'Registrasi gagal: ' . $errorMessage];
        }
    }
}

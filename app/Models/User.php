<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public $timestamps = false;

    protected $table = 'user';
    protected $primaryKey = 'iduser';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'idrole',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'iduser', 'idrole')->withPivot('status');
    }

    public static function findUserByEmail($email)
    {
        return self::with('roles')->where('email', $email)->first();
    }

    public static function getAllUsers()
    {
        return self::with('roles')->orderBy('nama', 'asc')->get();
    }

    public static function getUserById($iduser)
    {
        return self::with('roles')->find($iduser);
    }

    public static function deleteUser($iduser)
    {
        return DB::transaction(function () use ($iduser) {
            $user = self::find($iduser);
            if (!$user) {
                return ['status' => 'error', 'message' => "User dengan ID $iduser tidak ditemukan."];
            }

            // Since it's a belongsTo relationship, we don't need to detach.
            // $user->role()->dissociate(); // Optional: if you want to nullify the foreign key
            $user->delete();

            return ['status' => 'success', 'message' => 'User berhasil dihapus.'];
        });
    }

    public static function updateUser($iduser, $nama, $email, $idrole, $password = null)
    {
        return DB::transaction(function () use ($iduser, $nama, $email, $idrole, $password) {
            $user = self::find($iduser);
            if (!$user) {
                return ['status' => 'error', 'message' => "User dengan ID $iduser tidak ditemukan."];
            }

            $userData = [
                'nama' => $nama,
                'email' => $email,
                'idrole' => $idrole,
            ];

            if ($password) {
                $userData['password'] = Hash::make($password);
            }

            $user->update($userData);

            return ['status' => 'success', 'message' => 'Data user berhasil diperbarui.'];
        });
    }

    public static function searchUsers($keyword)
    {
        return self::with('roles')
            ->where('nama', 'like', "%$keyword%")
            ->orWhere('email', 'like', "%$keyword%")
            ->orderBy('nama', 'asc')
            ->get();
    }

    public static function getUsersByRole($roleName)
    {
        return self::with('roles')
            ->whereHas('roles', function ($query) use ($roleName) {
                $query->where('nama_role', $roleName);
            })
            ->orderBy('nama', 'asc')
            ->get();
    }

    public static function createUser($nama, $email, $password, $idrole)
    {
        return DB::transaction(function () use ($nama, $email, $password, $idrole) {
            $user = self::create([
                'nama' => $nama,
                'email' => $email,
                'password' => Hash::make($password),
                'idrole' => $idrole,
            ]);

            return ['status' => 'success', 'message' => 'User berhasil dibuat.', 'user' => $user];
        });
    }

    public function hasRole($roleName)
    {
        return $this->roles()->where('nama_role', $roleName)->exists();
    }

    public function getRoleNames()
    {
        return $this->roles()->pluck('nama_role')->toArray();
    }
}

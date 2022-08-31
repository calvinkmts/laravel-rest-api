<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class MhAdmin extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $table = 'mhadmin';
    protected $primaryKey = 'nomor';

    protected $attributes = [
        'tgl_lahir' => "0001-01-01",
        'nama_depan' => "",
        'nama_belakang' => "",
        'gender' => "",
        'alamat' => "",
        'keterangan' => "",
        'catatan' => "",
        'status_tt' => "",
        'ditt_oleh' => "",
    ];

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diubah_pada';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'email',
        'telepon',
        'kode',
        'sandi',
        'pin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'pin',
        'status_aktif',
        'dibuat_oleh',
        'dibuat_pada',
        'diubah_oleh',
        'diubah_pada',
        'dihapus_oleh',
        'dihapus_pada',
        'status_disetujui',
        'disetujui_oleh',
        'disetujui_pada',
        'dibatalkan_oleh',
        'dibatalkan_pada',
        'status_print',
        'diprint_oleh',
        'diprint_pada',
        'status_tt',
        'ditt_oleh',
        'ditt_pada',
    ];

    /**
     * Add a mutator to ensure hashed passwords
     */
    public function setSandiAttribute($password)
    {
        $this->attributes['sandi'] = md5($password);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}

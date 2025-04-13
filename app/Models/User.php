<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $timestamps = false;
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'status_aktif',
        'tanggal_login',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = md5($value);
    }

    // Fungsi untuk mendapatkan waktu login terakhir
    public function getLastLoginAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value);
        }
        return null;
    }

    public function validatePassword($inputPassword, $hashedPassword)
    {
        // Gunakan md5 untuk mengenkripsi kata sandi yang dimasukkan pengguna
        $hashedInputPassword = md5($inputPassword);
        
        // Bandingkan hasil hash kata sandi yang dimasukkan dengan kata sandi yang disimpan di database
        return $hashedInputPassword === $hashedPassword;
    }
}

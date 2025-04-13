<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelangganPembelianModel extends Model
{
    use HasFactory;
    public $table = 'pelanggan_pembeli';
    public $timestamps = false;

    protected $fillable = [
        'nama_pelanggan',
        'alamat'
    ];
}

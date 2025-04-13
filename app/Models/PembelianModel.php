<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianModel extends Model
{
    use HasFactory;

    public $table = 'pembelian';
    public $timestamps = false;

    protected $primaryKey = 'nomor_nota';
    protected $keyType = 'string';

    protected $fillable = 
    [
        'nomor_nota',
        'tanggal_nota',
        'nama_pelanggan',
        'alamat',
        'total',
        'nama_sales',
        'nama_user',
        'tanggal_log',
        'jatuh_tempo',

    ];
}

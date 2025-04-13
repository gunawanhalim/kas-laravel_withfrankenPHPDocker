<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UtangModel extends Model
{
    use HasFactory;

    public $table = 'utang';
    // protected $primaryKey = 'nomor_bukti';
    // protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'nomor_bukti',
        'nama_akun',
        'kategori',
        'nama_user',
        'tanggal_log',
        'nomor_nota',
        'jumlah',
        'nama_pelanggan',
        'nama_sales',
        'tanggal_bukti',
        
        'jatuh_tempo',
        
    ];

    public function kas_bank(): BelongsTo
    {
        return $this->BelongsTo(kas_bank::class);
    }
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(CategorieSupplierModel::class,'kategori','name');
    }
}

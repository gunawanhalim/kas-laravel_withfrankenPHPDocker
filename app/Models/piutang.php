<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class piutang extends Model
{
    use HasFactory;

    public $table = 'piutang';
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
        'tanggal_bukti',

        'jatuh_tempo',

    ];

    public function kas_bank(): BelongsTo
    {
        return $this->BelongsTo(kas_bank::class);
    }

    public function kategoriSupplier(): BelongsTo
    {
        return $this->belongsTo(CategorieSupplierModel::class,'kategori','name');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(CategorieSupplierModel::class,'kategori','name');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class kas_bank extends Model
{
    use HasFactory;
    public $table = 'kas_bank';
    public $timestamps = false;
    protected $fillable = [
        'nama_akun',
        'nama_user',
        'tanggal_log',
        'jumlah',
        'tanggal_bukti',
        'subcategories_id',
        'kategori',
        'from',
        'keterangan',
        'nama_pelanggan',
        'nama_sales_utang',
        'nomor_bukti',
        'from_account_id',
        'to_account_id'
    ];

    public function categories(): BelongsTo
    {
        return $this->BelongsTo(categories::class,'subcategories_id');
    }

    public function subcategories(): BelongsTo
    {
        return $this->BelongsTo(subcategories::class,'subcategories_id');
    }

    public function fromAccount(): BelongsTo
    {
        return $this->BelongsTo(akun_kas::class);
    }
    public function toAccount(): BelongsTo
    {
        return $this->BelongsTo(akun_kas::class);
    }
    

    public function akun_kas(): HasMany
    {
        return $this->HasMany(akun_kas::class,'nama_akun');
    }

    public function akunKas(): BelongsTo
    {
        return $this->belongsTo(akun_kas::class, 'nama_akun', 'nama_akun');
    }

    // public function transferTo(kas_bank $toAccount, $amount, $description = null)
    // {
    //     // Pastikan saldo mencukupi
    //     if ($this->jumlah < $amount) {
    //         throw new \Exception('Saldo tidak mencukupi untuk transfer.');
    //     }

    //     // Kurangi saldo dari akun asal
    //     $this->jumlah -= $amount;
    //     $this->save();

    //     // Tambahkan saldo ke akun tujuan
    //     $toAccount->jumlah += $amount;
    //     $toAccount->save();

    //     // Buat catatan transaksi untuk transfer
    //     kas_bank::create([
    //         'tanggal_bukti' => now(),
    //         'nama_akun' => $this->nama_akun,
    //         'subcategories_id' => $this->subcategories_id,
    //         'kategori' => 'Transfer Out',
    //         'jumlah' => -$amount,
    //         'keterangan' => $description,
    //         'nama_user' => auth()->user()->name,
    //         'tanggal_log' => now(),
    //     ]);

    //     kas_bank::create([
    //         'tanggal_bukti' => now(),
    //         'nama_akun' => $toAccount->nama_akun,
    //         'subcategories_id' => $toAccount->subcategories_id,
    //         'kategori' => 'Transfer In',
    //         'jumlah' => $amount,
    //         'keterangan' => $description,
    //         'nama_user' => auth()->user()->name,
    //         'tanggal_log' => now(),
    //     ]);
    // }
}

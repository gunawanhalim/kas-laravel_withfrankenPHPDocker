<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class akun_kas extends Model
{
    use HasFactory;
    public $table = 'akun_kas';
    // protected $primaryKey = 'akun_kas';
    // protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'nama_akun',
        'tampil',
    ];

    public function kas_bank(): HasMany
    {
        return $this->hasMany(kas_bank::class,'nama_akun','nama_akun');
    }

    public function nama_akun_bank_kas(): HasMany
    {
        return $this->HasMany(kas_bank::class,'nama_akun','nama_akun');
    }

    public function subcategories(): HasMany
    {
        return $this->HasMany(subcategories::class);
    }

    public function update(array $attributes = [], array $options = [])
    {
        $updated = parent::update($attributes, $options);

        // Update nama_akun di kas_bank yang memiliki nama_akun yang sama
        if ($updated) {
            kas_bank::where('nama_akun', $this->nama_akun)
                   ->update(['nama_akun' => $attributes['nama_akun']]);
        }

        return $updated;
    }
}

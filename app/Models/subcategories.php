<?php

namespace App\Models;

use App\Http\Controllers\KategoriController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class subcategories extends Model
{
    use HasFactory;
    public $table = 'subcategories';
    public $timestamps = false;
    protected $fillable =
    [
        'nama_akun',
        'kategori_id',
        'name'
    ];

    public function kategori(): BelongsTo
    {
        return $this->BelongsTo(categories::class);
    }
}

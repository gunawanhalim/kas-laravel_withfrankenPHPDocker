<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class categories extends Model
{
    use HasFactory;

    public $table = 'categories';
    public $timestamps = false;

    protected $fillable = [
        '',
    ];

    public function subcategories(): BelongsTo
    {
        return $this->BelongsTo(subcategories::class);
    }
    
}

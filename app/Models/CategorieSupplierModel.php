<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieSupplierModel extends Model
{
    use HasFactory;

    protected $table = 'categori_suppliers';
    public $timestamps = false;
    protected $fillable = 
    [
        'kategori',
        'name',
        'created_at',
        'update_at',
    ];
}

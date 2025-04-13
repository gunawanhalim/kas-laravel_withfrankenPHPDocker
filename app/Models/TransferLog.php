<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferLog extends Model
{
    use HasFactory;

    protected $table = 'transfer_logs';

    protected $fillable = [
        'from_account',
        'to_account',
        'amount',
        'description',
    ];

    // Relationship example (if needed)
    public function fromAccount()
    {
        return $this->belongsTo(kas_bank::class, 'from_account');
    }

    public function toAccount()
    {
        return $this->belongsTo(kas_bank::class, 'to_account');
    }
}

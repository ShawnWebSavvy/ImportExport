<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MercantileTransactionRejections extends Model
{
    use HasFactory;

    protected $fillable = [
        'policy_id',
        'transaction_id',
        'Processed',
    ];
}

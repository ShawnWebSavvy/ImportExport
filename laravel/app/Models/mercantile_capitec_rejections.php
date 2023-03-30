<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mercantile_capitec_rejections extends Model
{
    use HasFactory;

    protected $fillable = [
        'policy_id',
        'transaction_id',
        'Amount',
        'Processed',
    ];
}

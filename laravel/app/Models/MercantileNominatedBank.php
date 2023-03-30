<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MercantileNominatedBank extends Model
{
    use HasFactory;

    protected $fillable = [
        'NominatedAccountNumber',
        'ChargesAccountNumber',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MercantileUserPolicy extends Model
{
    use HasFactory;

    protected $fillable = [
        'PolicyNumber',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class generation_number extends Model
{
    use HasFactory;

    protected $fillable = [
        'generation_number_botswana',
        'generation_number_capitec',
        'bank'
    ];
}

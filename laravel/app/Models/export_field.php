<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class export_field extends Model
{
    use HasFactory;

    protected $fillable = [
        'field_1',
        'field_2',
        'field_3',
        'field_4',
        'field_5',
        'field_6',
        'dateField_1',
        'dateField_2',
        'dateField_3',
        'dateField_4',
    ];
}

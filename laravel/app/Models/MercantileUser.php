<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MercantileUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'AccountHolderFullName',
        'AccountHolderSurame',
        'AccountHolderInitials',
        'ClientType',
        'policy_id',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customerInfo extends Model
{
    use HasFactory;
    protected $fillable = [
        'policy_id',
        'AccountHolderFullName',
        'AccountHolderSurame',
        'AccountHolderInitials',
        'ClientType',
        'UserAccountNumber',
        'UserBranchCode',
        'UserBankType'
    ];

    public static function addNew($data){
        self::insert($data);
    }
}

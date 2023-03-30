<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MercantileUserBank extends Model
{
    use HasFactory;

    protected $fillable = [
        'UserAccountNumber',
        'UserBranchCode',
        'UserBankType',
        'policy_id',
    ];

    public static function addNew($data){
        self::upsert(
            $data, ['policy_id'], ['policy_id', 'UserAccountNumber', 'UserBranchCode', 'UserBankType']
        );
    }

    
}

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

    public static function addNew($data){
        self::upsert(
            $data, ['policy_id'], ['policy_id', 'AccountHolderFullName', 'AccountHolderSurame', 'AccountHolderInitials', 'ClientType']
        );
    }
}
/*
self::upsert(
    $data, ['PolicyNumber'], ['PolicyNumber', 'dummy_data_Capitec_active']
);
*/
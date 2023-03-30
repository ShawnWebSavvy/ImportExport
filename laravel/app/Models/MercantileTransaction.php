<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MercantileTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'RecordIdentifier',
        'PaymentReference',
        'Amount',
        'ActionDate',
        'TransactionUniqueID',
        'StatementReference',
        'CycleDate',
        'TransactionType',
        'ServiceType',
        'OriginalPaymentReference',
        'EntryClass',
        'NominatedAccountReference',
        'BDF_Indicator',
        'policy_id',
        'Processed',
    ];

    public static function addNew($data){
        //dd($data);
        self::insert($data);
    }
}

/*
insert into `mercantile_transactions` 
(`ActionDate`, `Amount`, `BDF_Indicator`, `CycleDate`, `EntryClass`, `NominatedAccountReference`, `OriginalPaymentReference`, `PaymentReference`, `Processed`, `RecordIdentifier`, `ServiceType`, `StatementReference`, `TransactionOrder`, `TransactionType`, `TransactionUniqueID`, `policy_id`) 
values 
('20221101', '000000017300', ' ', '221027', '21', 'SCORPION SL-198131000000CPS7A', ' ', '0000002517202210270039580021358342', '0', '02', '04', 'SCORPION' , '2517202210', '0000', 'SCORPION SL-19813100000221027', 'SL-19813100000')
*/

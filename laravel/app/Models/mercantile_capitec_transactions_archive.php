<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mercantile_capitec_transactions_archive extends Model
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
}

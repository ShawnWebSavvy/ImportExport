<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileImportCapitec extends Model
{
    use HasFactory;

    protected $fillable = [
        'AccountHolderFullName',
        'AccountHolderSurame',
        'AccountHolderInitials',
        'ClientsAccountNumber',
        'ClientsBranchCode',
        'DestinationAccountNumber',
        'DestinationBranchCode',
        'PaymentReference',
        'Amount',
        'ActionDate',
        'TransactionUniqueID',
        'StatementReference',
        'ContractReference',
        'CycleDate',
        'TransactionType',
        'ClientType',
        'ChargesAccountNumber',
        'ServiceType',
        'OriginalPaymentReference',
        'EntryClass',
        'NominatedAccountReference',
        'BDF_Indicator',
        'Guid',
    ];
}

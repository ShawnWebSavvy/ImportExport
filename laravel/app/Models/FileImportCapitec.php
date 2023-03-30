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
        'BankType',
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
        'NominatedAccountNumber',
        'RecordIdentifier',
        'BDF_Indicator',
        'Guid',
    ];
}
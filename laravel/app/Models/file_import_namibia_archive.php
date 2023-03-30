<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class file_import_namibia_archive extends Model
{
    use HasFactory;

    protected $fillable = [
        'ContractNumber',
        'ReferenceNumber',
        'RecipientAccountHolderName',
        'RecipientAccountHolderSurname',
        'RecipientAccountHolderInitials',
        'RecipientAccountHolderAbbreviatedName',
        'OrganizatonName',
        'OrganizationCode',
        'BranchCode',
        'BranchSwiftBicCode',
        'RecipientAccountNumber',
        'RecipientNonStandardAccountNumber',
        'RecipientAccountType',
        'RecipientAmount',
        'ActionDate',
        'EntryType',
        'TransactionType',
        'ServiceType',
        'Tracking',
        'SequenceNumber',
        'SettlementReferenceTraceCode',
        'ContractReference',
        'CollectionReason',
        'ImportDate',
        'Guid',
        'ExportDate',
    ];
}

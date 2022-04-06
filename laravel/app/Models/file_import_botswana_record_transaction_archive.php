<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class file_import_botswana_record_transaction_archive extends Model
{
    use HasFactory;

    protected $fillable = [
        'RecordIdentifier',
        'UserBranch',
        'UserAccountNumber',
        'UserCode',
        'SequenceNumber',
        'HomingBranch',
        'HomingAccountNumber',
        'AccountType',
        'Amount',
        'ActionDate',
        'EntryType',
        'TaxCode',
        'UserAbbreviatedName',
        'UserReference',
        'HomingAccountName',
        'NonStandardAccountNumber',
        'HomingInstitution',
        'ImportDate',
        'Guid',
        'ExportDate',
    ];
}

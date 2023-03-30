<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class file_import_botswana_record_contra_archive extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'RecordIdentifier',
        'UserBranch',
        'UserCode',
        'SequenceNumber',
        'HomingBranch',
        'HomingAccountNumber',
        'AccountType',
        'Amount',
        'ActionDate',
        'EntryType',
        'UserAbbreviatedName',
        'UserReference',
        'AccountName',
        'ImportDate',
        'Guid',
        'ExportDate',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class file_import_botswana_record_user_trailer_archive extends Model
{
    use HasFactory;

    protected $fillable = [
        'RecordIdentifier',
        'UserCode',
        'SequenceNumberFirst',
        'SequenceNumberLast',
        'ActionDateFirst',
        'ActionDateLast',
        'NumberDebitRecords',
        'NumberCreditRecords',
        'NumberContraRecords',
        'TotalDebitValue',
        'TotalCreditValue',
        'HashTotalofHomingAccountNumbers',
        'ImportDate',
        'Guid',
        'ExportDate',
    ];
}

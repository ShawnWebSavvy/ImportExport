<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class file_import_botswana_record_user_header_archive extends Model
{
    use HasFactory;

    protected $fillable = [
        'RecordIdentifier',
        'UserCode',
        'CreationDate',
        'PurgeDate',
        'ActionDateFirst',
        'ActionDateLast',
        'SequenceNumber',
        'GenerationNumber',
        'Service',
        'ImportDate',
        'Guid',
        'ExportDate',
    ];
}

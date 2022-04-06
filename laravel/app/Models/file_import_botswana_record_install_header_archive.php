<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class file_import_botswana_record_install_header_archive extends Model
{
    use HasFactory;

    protected $fillable = [
        'RecordIdentifier',
        'VolumeNumber',
        'TapeSerialNumber',
        'InstallationIDfrom',
        'InstallationIDto',
        'CreationDate',
        'PurgeDate',
        'GenerationNumber',
        'BlockLength',
        'RecordLength',
        'Service',
        'ImportDate',
        'Guid',
        'ExportDate',
    ];
}

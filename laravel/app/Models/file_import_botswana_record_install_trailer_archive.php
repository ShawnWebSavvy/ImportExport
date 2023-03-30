<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class file_import_botswana_record_install_trailer_archive extends Model
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
        'BlockCount',
        'RecordCount',
        'UserHeaderTrailerCount',
        'ImportDate',
        'Guid',
        'ExportDate',
    ];
}

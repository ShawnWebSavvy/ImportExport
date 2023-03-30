<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileImportBotswanaRecordInstallHeader extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    
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
    ];
}

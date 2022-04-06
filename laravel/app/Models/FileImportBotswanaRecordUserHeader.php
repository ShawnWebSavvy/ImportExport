<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileImportBotswanaRecordUserHeader extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

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
    ];
}

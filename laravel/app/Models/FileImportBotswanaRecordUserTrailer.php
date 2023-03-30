<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileImportBotswanaRecordUserTrailer extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

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
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileImportBotswanaRecordContra extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    
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
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileImportNamibia extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ContractNumber',
        'ReferenceNumber',
        'RecipientAccountHolderName',
        'RecipientAccountHolderSurname',
        'RecipientAccountHolderInitials',
        'RecipientAccountHolderAbbreviatedName',
        'OrganizatonName',
        'OrganizationCode',
        'BranchCode',
        'BranchSwiftBicCode',
        'RecipientAccountNumber',
        'RecipientNonStandardAccountNumber',
        'RecipientAccountType',
        'RecipientAmount',
        'ActionDate',
        'EntryType',
        'TransactionType',
        'ServiceType',
        'Tracking',
        'SequenceNumber',
        'SettlementReferenceTraceCode',
        'ContractReference',
        'CollectionReason',
        'ImportDate',
        'Guid',
    ];
}

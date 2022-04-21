<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileImportBotswana extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'RecipientAccountHolderName',
        'RecipientAccountHolderSurname',
        'RecipientAccountHolderInitials',
        'RecipientAccountHolderAbbreviatedName',
        'RecipientID',
        'BranchCode',
        'RecipientAccountNumber',
        'RecipientNonStandardAccountNumber',
        'RecipientAccountType',
        'AccountReference',
        'RecipientAmount',
        'PolicyNumber',
        'Guid',
        'ActionDate',
    ];
}

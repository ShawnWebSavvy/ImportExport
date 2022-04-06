<?php

namespace App\Exports;

use App\Models\FileImportBotswanaRecordTransaction;
use App\Models\file_import_botswana_record_transaction_archive;
use App\Models\export_field;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class BotswanaRecordTransactionExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        $fields = export_field::all();
        foreach($fields as $field){
            $actionDate = $field->dateField_1;
        }

        $dateDate = explode('-', $actionDate);
        $dateDate = implode("", $dateDate);
        
        $headers0 = ['RecordInstallHeader '.$dateDate];
        $headers1 = [
            'Record Identifier',
            'User Branch',
            'User Account Number',
            'User Code',
            'Sequence Number',
            'Homing Branch',
            'Homing Account Number',
            'Account Type',
            'Amount', 
            'Action Date', 
            'Entry Type', 
            'Tax Code', 
            'User Abbreviated Name', 
            'User Reference', 
            'Homing Account Name', 
            'Non Standard Account Number', 
            'Homing Institution', 
        ];
        return [$headers0,$headers1];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // get all fields by action date - set by user on file-import blade
        
        $fields = export_field::all();
        foreach($fields as $field){
            $actionDate = $field->dateField_1;
        }
        

        $export = DB::table('file_import_botswana_record_transactions')
        ->where('ActionDate', '=', $actionDate)
        //->orderBy('RecipientAccountHolderName','asc')
        ->get();

        //$array = $exportNamibia->map(function ($export, $key) {
        $array = $export->map(function ($export) {
            return 
            [
                //'id' => $export->RecipientAccountHolderName,
                $export->RecordIdentifier,
                $export->UserBranch,
                $export->UserAccountNumber,
                $export->UserCode,
                $export->SequenceNumber,
                $export->HomingBranch,
                $export->HomingAccountNumber,
                $export->AccountType,
                $export->Amount,
                $export->ActionDate,
                $export->EntryType,
                $export->TaxCode,
                $export->UserAbbreviatedName,
                $export->UserReference,
                $export->HomingAccountName,
                $export->NonStandardAccountNumber,
                $export->HomingInstitution,
            ];
        });

        // delete from table
        DB::table('file_import_botswana_record_transactions')->where('ActionDate', $actionDate)->delete();
        DB::table('file_import_botswana_record_transactions')->delete();

        // keep a record of details
        foreach($export as $value){
            DB::table('file_import_botswana_record_transaction_archives')->insert(
                array(
                    //'RecipientAccountHolderName' => $value->RecipientAccountHolderName,
                    'RecordIdentifier' => $value->RecordIdentifier,
                    'UserBranch' => $value->UserBranch,
                    'UserAccountNumber' => $value->UserAccountNumber,
                    'UserCode' => $value->UserCode,
                    'SequenceNumber' => $value->SequenceNumber,
                    'HomingBranch' => $value->HomingBranch,
                    'HomingAccountNumber' => $value->HomingAccountNumber,
                    'AccountType' => $value->AccountType,
                    'Amount' => $value->Amount,
                    'ActionDate' => $value->ActionDate,
                    'EntryType' => $value->EntryType,
                    'TaxCode' => $value->TaxCode,
                    'UserAbbreviatedName' => $value->UserAbbreviatedName,
                    'UserReference' => $value->UserReference,
                    'HomingAccountName' => $value->HomingAccountName,
                    'NonStandardAccountNumber' => $value->NonStandardAccountNumber,
                    'HomingInstitution' => $value->HomingInstitution,
                )
            );
        }
        return $array;
        //return FileImportBotswanaRecordInstallHeader::all();
    }
}

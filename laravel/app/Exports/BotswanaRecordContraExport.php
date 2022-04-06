<?php

namespace App\Exports;

use App\Models\FileImportBotswanaRecordContra;
use App\Models\file_import_botswana_record_contra_archive;
use App\Models\export_field;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class BotswanaRecordContraExport implements FromCollection, WithHeadings
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
            'User Code',
            'Sequence Number',
            'Homing Branch',
            'Homing Account Number',
            'Account Type',
            'Amount',
            'Action Date', 
            'Entry Type', 
            'User Abbreviated Name', 
            'User Reference', 
            'Account Name', 
        ];
        return [$headers0,$headers1];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // get all fields by action date - set by user on file-import blade
        /*
        $fields = export_field::all();
        foreach($fields as $field){
            $actionDate = $field->dateField_1;
        }
        */

        $export = DB::table('file_import_botswana_record_contras')
        //->where('ActionDate', '=', $actionDate)
        //->orderBy('RecipientAccountHolderName','asc')
        ->get();

        //$array = $exportNamibia->map(function ($export, $key) {
        $array = $export->map(function ($export) {
            return 
            [
                //'id' => $export->RecipientAccountHolderName,
                $export->RecordIdentifier,
                $export->UserBranch,
                $export->UserCode,
                $export->SequenceNumber,
                $export->HomingBranch,
                $export->HomingAccountNumber,
                $export->AccountType,
                $export->Amount,
                $export->ActionDate,
                $export->EntryType,
                $export->UserAbbreviatedName,
                $export->UserReference,
                $export->AccountName,
            ];
        });

        // delete from table
        //DB::table('file_import_botswana_record_contras')->where('ActionDate', $actionDate)->delete();
        DB::table('file_import_botswana_record_contras')->delete();

        // keep a record of details
        foreach($export as $value){
            DB::table('file_import_botswana_record_contra_archives')->insert(
                array(
                    //'RecipientAccountHolderName' => $value->RecipientAccountHolderName,
                    'RecordIdentifier' => $value->RecordIdentifier,
                    'UserBranch' => $value->UserBranch,
                    'UserCode' => $value->UserCode,
                    'SequenceNumber' => $value->SequenceNumber,
                    'HomingBranch' => $value->HomingBranch,
                    'HomingAccountNumber' => $value->HomingAccountNumber,
                    'AccountType' => $value->AccountType,
                    'Amount' => $value->Amount,
                    'ActionDate' => $value->ActionDate,
                    'EntryType' => $value->EntryType,
                    'UserAbbreviatedName' => $value->UserAbbreviatedName,
                    'UserReference' => $value->UserReference,
                    'AccountName' => $value->AccountName,
                )
            );
        }
        return $array;
        //return FileImportBotswanaRecordInstallHeader::all();
    }
}

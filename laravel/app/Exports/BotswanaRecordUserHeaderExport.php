<?php

namespace App\Exports;

use App\Models\FileImportBotswanaRecordUserHeader;
use App\Models\file_import_botswana_record_user_header_archive;
use App\Models\export_field;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class BotswanaRecordUserHeaderExport implements FromCollection, WithHeadings
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
            'User Code',
            'Creation Date',
            'Purge Date',
            'Action Date First',
            'Action Date Last',
            'Sequence Number',
            'Generation Number',
            'Service', 
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

        $export = DB::table('file_import_botswana_record_user_headers')
        //->where('ActionDate', '=', $actionDate)
        //->orderBy('RecipientAccountHolderName','asc')
        ->get();

        //$array = $exportNamibia->map(function ($export, $key) {
        $array = $export->map(function ($export) {
            return 
            [
                //'id' => $export->RecipientAccountHolderName,
                $export->RecordIdentifier,
                $export->UserCode,
                $export->CreationDate,
                $export->PurgeDate,
                $export->ActionDateFirst,
                $export->ActionDateLast,
                $export->SequenceNumber,
                $export->GenerationNumber,
                $export->Service,
            ];
        });

        // delete from table
        //DB::table('file_import_botswana_record_user_headers')->where('ActionDate', $actionDate)->delete();
        DB::table('file_import_botswana_record_user_headers')->delete();

        // keep a record of details
        foreach($export as $value){
            DB::table('file_import_botswana_record_user_header_archives')->insert(
                array(
                    //'RecipientAccountHolderName' => $value->RecipientAccountHolderName,
                    'RecordIdentifier' => $value->RecordIdentifier,
                    'UserCode' => $value->UserCode,
                    'CreationDate' => $value->CreationDate,
                    'PurgeDate' => $value->PurgeDate,
                    'ActionDateFirst' => $value->ActionDateFirst,
                    'ActionDateLast' => $value->ActionDateLast,
                    'SequenceNumber' => $value->SequenceNumber,
                    'GenerationNumber' => $value->GenerationNumber,
                    'Service' => $value->Service,
                )
            );
        }
        return $array;
        //return FileImportBotswanaRecordInstallHeader::all();
    }
}
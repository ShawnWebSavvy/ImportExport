<?php

namespace App\Exports;

use App\Models\FileImportBotswanaRecordUserTrailer;
use App\Models\file_import_botswana_record_user_trailer_archive;
use App\Models\export_field;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class BotswanaRecordUserTrailerExport implements FromCollection, WithHeadings
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
            'Sequence Number First',
            'Sequence Number Last',
            'Action Date Last',
            'Number Debit Records',
            'Number Credit Records',
            'Number Contra Records',
            'Total Debit Value', 
            'Total Credit Value', 
            'Hash Total of Homing Account Numbers', 
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

        $export = DB::table('file_import_botswana_record_user_trailers')
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
                $export->SequenceNumberFirst,
                $export->SequenceNumberLast,
                $export->ActionDateLast,
                $export->NumberDebitRecords,
                $export->NumberCreditRecords,
                $export->NumberContraRecords,
                $export->TotalDebitValue,
                $export->TotalCreditValue,
                $export->HashTotalofHomingAccountNumbers,
            ];
        });

        // delete from table
        //DB::table('file_import_botswana_record_user_trailers')->where('ActionDate', $actionDate)->delete();
        DB::table('file_import_botswana_record_user_trailers')->delete();

        // keep a record of details
        foreach($export as $value){
            DB::table('file_import_botswana_record_user_trailer_archives')->insert(
                array(
                    //'RecipientAccountHolderName' => $value->RecipientAccountHolderName,
                    'RecordIdentifier' => $value->RecordIdentifier,
                    'UserCode' => $value->UserCode,
                    'SequenceNumberFirst' => $value->SequenceNumberFirst,
                    'SequenceNumberLast' => $value->SequenceNumberLast,
                    'ActionDateLast' => $value->ActionDateLast,
                    'NumberDebitRecords' => $value->NumberDebitRecords,
                    'NumberCreditRecords' => $value->NumberCreditRecords,
                    'NumberContraRecords' => $value->NumberContraRecords,
                    'TotalDebitValue' => $value->TotalDebitValue,
                    'TotalCreditValue' => $value->TotalCreditValue,
                    'HashTotalofHomingAccountNumbers' => $value->HashTotalofHomingAccountNumbers,
                )
            );
        }
        return $array;
        //return FileImportBotswanaRecordInstallHeader::all();
    }
}
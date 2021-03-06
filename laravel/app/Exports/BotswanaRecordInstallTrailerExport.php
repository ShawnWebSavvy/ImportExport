<?php

namespace App\Exports;

use App\Models\FileImportBotswanaRecordInstallTrailer;
use App\Models\file_import_botswana_record_install_trailer_archive;
use App\Models\export_field;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class BotswanaRecordInstallTrailerExport implements FromCollection, WithHeadings
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
            'Volume Number',
            'Tape Serial Number',
            'Installation ID from',
            'Installation ID to',
            'Creation Date',
            'Purge Date',
            'Generation Number',
            'Block Length', 
            'Record Length', 
            'Service', 
            'Block Count', 
            'Record Count', 
            'User Header Trailer Count', 
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

        $export = DB::table('file_import_botswana_record_install_trailers')
        //->where('ActionDate', '=', $actionDate)
        //->orderBy('RecipientAccountHolderName','asc')
        ->get();

        //$array = $exportNamibia->map(function ($export, $key) {
        $array = $export->map(function ($export) {
            return 
            [
                //'id' => $export->RecipientAccountHolderName,
                $export->RecordIdentifier,
                $export->VolumeNumber,
                $export->TapeSerialNumber,
                $export->InstallationIDfrom,
                $export->InstallationIDto,
                $export->CreationDate,
                $export->PurgeDate,
                $export->GenerationNumber,
                $export->BlockLength,
                $export->RecordLength,
                $export->Service,
                $export->BlockCount,
                $export->RecordCount,
                $export->UserHeaderTrailerCount,
            ];
        });

        // delete from table
        //DB::table('file_import_botswana_record_install_trailers')->where('ActionDate', $actionDate)->delete();
        DB::table('file_import_botswana_record_install_trailers')->delete();

        // keep a record of details
        foreach($export as $value){
            DB::table('file_import_botswana_record_install_trailer_archives')->insert(
                array(
                    //'RecipientAccountHolderName' => $value->RecipientAccountHolderName,
                    'RecordIdentifier' => $value->RecordIdentifier,
                    'VolumeNumber' => $value->VolumeNumber,
                    'TapeSerialNumber' => $value->TapeSerialNumber,
                    'InstallationIDfrom' => $value->InstallationIDfrom,
                    'InstallationIDto' => $value->InstallationIDto,
                    'CreationDate' => $value->CreationDate,
                    'PurgeDate' => $value->PurgeDate,
                    'GenerationNumber' => $value->GenerationNumber,
                    'BlockLength' => $value->BlockLength,
                    'RecordLength' => $value->RecordLength,
                    'Service' => $value->Service,
                    'BlockCount' => $value->BlockCount,
                    'RecordCount' => $value->RecordCount,
                    'UserHeaderTrailerCount' => $value->UserHeaderTrailerCount,
                )
            );
        }
        return $array;
        //return FileImportBotswanaRecordInstallHeader::all();
    }
}
<?php

namespace App\Exports;

use App\Models\FileImportNamibia;
use App\Models\file_import_namibia_archive;
use App\Models\export_field;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class NamibiaExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        $fields = export_field::all();
        foreach($fields as $field){
            $actionDate = $field->dateField_1;
        }

        $dateDate = explode('-', $actionDate);
        $dateDate = implode("", $dateDate);
        
        $headers0 = ['lwise '.$dateDate.'                      '];
        $headers1 = ['BInSol - U ver 1.00                      '];
        $headers2 = [$actionDate.'                             '];
        $headers3 = ['62267945156                              '];
        $headers4 = [
            'RECIPIENT NAME',
            'RECIPIENT ACCOUNT',
            'RECIPIENT ACCOUNT TYPE',
            'BIC CODE',
            'AMOUNT',
            'CONTRACT REFERENCE',
            'TRACKING',
            'ABBREVIATED NAME',
            'REASON FOR COLLECTION', 
        ];
        return [$headers0,$headers1,$headers2,$headers3,$headers4];
    }
    /**
    * 
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // get all fields by action date - set by user on file-import blade
        $fields = export_field::all();
        foreach($fields as $field){
            $actionDate = $field->dateField_1;
        }

        $export = DB::table('file_import_namibias')
        ->where('ActionDate', '=', $actionDate)
        ->orderBy('RecipientAccountHolderName','asc')
        ->get();

        //$array = $exportNamibia->map(function ($export, $key) {
        $array = $export->map(function ($export) {
            return 
            [
                //'id' => $export->RecipientAccountHolderName,
                $export->RecipientAccountHolderName,
                $export->RecipientAccountNumber,
                $export->RecipientAccountType,
                $export->BranchSwiftBicCode,
                $export->RecipientAmount,
                $export->ContractReference,
                $export->Tracking,
                $export->RecipientAccountHolderAbbreviatedName,
                $export->CollectionReason,
            ];
        });
        
        // delete from table
        DB::table('file_import_namibias')->where('ActionDate', $actionDate)->delete();

        // keep a record of details
        foreach($export as $value){
            DB::table('file_import_namibia_archives')->insert(
                array(
                    'RecipientAccountHolderName' => $value->RecipientAccountHolderName,
                    'RecipientAccountNumber' => $value->RecipientAccountNumber,
                    'RecipientAccountType' => $value->RecipientAccountType,
                    'BranchSwiftBicCode' => $value->BranchSwiftBicCode,
                    'RecipientAmount' => $value->RecipientAmount,
                    'ContractReference' => $value->ContractReference,
                    'Tracking' => $value->Tracking,
                    'CollectionReason' => $value->CollectionReason,
                    'ActionDate' => $actionDate, 
                    'RecipientAccountHolderAbbreviatedName' => 'XXLWNAMI', 
                    'batch_number' => $value->batch_number,
                )
            );
        }
        return $array;
    }
}
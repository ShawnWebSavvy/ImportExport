<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;
use App\Models\Deduction;

use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;

class TransactionExport implements FromCollection, ShouldQueue
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Deduction::all();
        /*
        $export = DB::table('deductions')->get();
        //dd($export[0]->row);
        $array = $export->map(function ($export) {
            return [$export->row];
            //dd($export->row);
        });
        return $array;
        */
        /*
        $array = array();
        $array = LazyCollection::make(function () {
            $handle = fopen($_SESSION['pathToFile'],"r");
            while (($line = fgetcsv($handle, 4096)) !== false) {
                yield $line;
            }
            fclose($handle);
        })
        ->chunk(1000)
        ->each(function (LazyCollection $chunk) {
            $MercantileUserPolicy = $chunk->map(function ($line) {
                $PolicyNumber = substr($line[0], 104, 14);
                return [
                    'PolicyNumber' => $PolicyNumber,
                    'row' => $line[0]
                ];
            })->toArray();
            //Deduction::addNew($MercantileUserPolicy); 
            //dd($MercantileUserPolicy);
            //dd($MercantileUserPolicy);
            return [$MercantileUserPolicy];
        });
        //dd($array);
        return $array;
        //return $array;
        */


    }
    
}

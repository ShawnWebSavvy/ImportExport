<?php

namespace App\Exports;

use App\Models\FileImportBotswana;
use Maatwebsite\Excel\Concerns\FromCollection;

class MyExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return FileImportBotswana::all();
    }
}

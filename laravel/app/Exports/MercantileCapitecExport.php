<?php

namespace App\Exports;

use App\Models\MercantileTransaction;
use Maatwebsite\Excel\Concerns\FromCollection;

class MercantileCapitecExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        dd('export');
        return MercantileTransaction::all();
    }
}

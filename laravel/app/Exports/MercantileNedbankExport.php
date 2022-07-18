<?php

namespace App\Exports;

use App\Models\MercantileTransaction;
use Maatwebsite\Excel\Concerns\FromCollection;

class MercantileNedbankExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return MercantileTransaction::all();
    }
}

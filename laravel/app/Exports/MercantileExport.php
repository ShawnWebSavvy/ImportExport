<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\MercantileUser;
use App\Models\MercantileTransaction;
use App\Models\MercantileUserBank;
use App\Models\MercantileUserPolicy;
use App\Models\MercantileTransactionRejections;
use App\Models\MercantileNominatedBank;

class MercantileExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return MercantileTransaction::all();
    }
}

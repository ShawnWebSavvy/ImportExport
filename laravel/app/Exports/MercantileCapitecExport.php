<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\export_field;
use App\Models\generation_number;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

use App\Models\MercantileUser;
use App\Models\MercantileTransaction;
use App\Models\MercantileUserBank;
use App\Models\MercantileUserPolicy;
use App\Models\MercantileTransactionRejections;
use App\Models\MercantileNominatedBank;

use DateTime; 
use DateInterval;
use DatePeriod;

class MercantileCapitecExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        // format all dates to correct format
        $dateNow = date('Y-m-d');
        $dateNow = explode("-", $dateNow);
        $dateNow = implode("", $dateNow);
        $GLOBALS['dateNow'] = $dateNow = substr($dateNow, 2); 

        // action dates, from and to
        $fields = export_field::all();
        foreach($fields as $field){
            $actionDateFrom = $field->dateField_1;
            $actionDateTo = $field->dateField_2;
        }

        $actionDateFrom = explode("-", $actionDateFrom);
        $actionDateFrom = implode("", $actionDateFrom);
        $GLOBALS['actionDateFirst'] = $actionDateFrom = substr($actionDateFrom, 2); 

        $actionDateTo = explode("-", $actionDateTo);
        $actionDateTo = implode("", $actionDateTo);
        $GLOBALS['actionDateTo'] = $actionDateTo = $purgeDate = substr($actionDateTo, 2); 
        
        //generation number
        $query =  DB::table('generation_numbers')->orderBy('id', 'desc')
        ->where('bank', 'Capitec')
        ->first();
        // if database is empty (was unable to set a default value in migration)
        if(!$query){
            $generation_number = 0001;
        } else {
            // incremnt the generation number
            $generation_number = $query->generation_number_botswana + 1;
        }
        // if = 1000000, then reset, as it can only be 4 digits
        if($generation_number == 10000){
            $generation_number = 1;
        }
        // after increment, number can be less than 4 digits, so check and add back the 0's
        $str_length = strlen($generation_number);
        $zero = '0000';
        $generation_number = $zero . $generation_number;
        $generation_number = substr($generation_number, $str_length, 4);

        //delete rows that are not required
        DB::table('generation_numbers')
        ->where('bank', 'Capitec',)
        ->delete();

        // insert generation number, to keep refernce
        DB::table('generation_numbers')->insert(
            [
                'generation_number_botswana' => $generation_number,
                'bank' => 'Capitec',
            ]
        );

        // make global to insert call from trailer (footer) function
        $GLOBALS['generation_number'] = $generation_number;

        // set sequance number, in header will always be 000001
        //$GLOBALS['sequenceNumber'] = $sequance_number = 000001;

        //header row identifier = H04 always
        $header_row = 'H04';
        //user code, not sure what it is, but in documentation, states it will be provided
        $user_code = 'Test';

        // create header
        $header = $header_row.$user_code.$dateNow.$dateNow.$actionDateFrom.$actionDateTo.'000001'.$generation_number;
        
        return [$header];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $fields = export_field::all();
        foreach($fields as $field){
            $actionDateFrom = $field->dateField_1;
            $actionDateTo = $field->dateField_2;
        }

        // get all rows for thestandard transaction details
        $export = DB::table('mercantile_user_policies')
                ->join('mercantile_transactions', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_transactions.policy_id')
                ->join('mercantile_users', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_users.policy_id')
                ->join('mercantile_user_banks', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_user_banks.policy_id')
                ->where('mercantile_user_banks.UserBankType', '=', 'Capitec')
                ->whereBetween('mercantile_transactions.ActionDate', [$actionDateFrom, $actionDateTo])
                ->orderBy('ActionDate','asc')
                ->get();

        // get total records, to know when to run trailer record (last entry row)
        $GLOBALS['count'] = count($export) - 1;

        dd($GLOBALS['count']);


        $array = $export->map(function ($export, $key) { //$array = $export->map(function ($export) {
            $amount = $export->Amount;
            $policyNumber = $export->PolicyNumber;





            $std_transaction_record = [
                'qq'.$policyNumber.'.'.$amount.','
            ];
            //dd($amount);
            

            if($key == $GLOBALS['count']){
                //dd($key);

                $trailer_record = [
                    'the end '. $key
                ];
                return [$std_transaction_record, $trailer_record]; 
            }
            return [$std_transaction_record]; 
        });
        //dd($amount);
        //transaction type : 0000 = debit, 9999 = credit
        //dd($export);
        //dd('export');





        return $array;

        
    }
}

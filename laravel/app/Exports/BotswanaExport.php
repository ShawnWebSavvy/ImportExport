<?php

namespace App\Exports;

use App\Models\FileImportBotswana;
use App\Models\file_import_botswana_archive;
use App\Models\export_field;
use App\Models\generation_number;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

use DateTime; 
use DateInterval;
use DatePeriod;

class BotswanaExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        $fields = export_field::all();
        foreach($fields as $field){
            $actionDateFrom = $field->dateField_1;
            $actionDateTo = $field->dateField_2;
        }

        $query =  DB::table('generation_numbers')->orderBy('id', 'desc')->first();
        // incremnt the generation number
        $generation_number = $query->generation_number + 1;
        // if = 10000, then reset, as it can only be 4 digits
        if($generation_number == 10000){
            $generation_number = 1;
        }
        // after increment, number can be less than 4 digits, so check and add back the 0's
        $str_length = strlen($generation_number);
        $zero = '0000';
        $generation_number = $zero . $generation_number;
        $generation_number = substr($generation_number, $str_length, 4);

        // make global to insert call from trailer (footer) function
        $GLOBALS['generation_number'] = $generation_number;
        // insert generation number, to keep refernce
        DB::table('generation_numbers')->insert(
            ['generation_number' => $generation_number]
        );
    
        // format both dates to correct format
        $dateNow = date('Y-m-d');
        $dateNow = explode("-", $dateNow);
        $dateNow = implode("", $dateNow);
        $GLOBALS['dateNow'] = $dateNow = substr($dateNow, 2); 
        
        // purge date is the same as the last action date in range
        $purgeDate = $actionDateTo;
        $purgeDate = explode("-", $purgeDate);
        $purgeDate = implode("", $purgeDate);
        $GLOBALS['purgeDate'] = $actionDateTo = $purgeDate = substr($purgeDate, 2); 

        $actionDateFrom = explode("-", $actionDateFrom);
        $actionDateFrom = implode("", $actionDateFrom);
        $GLOBALS['actionDateFirst'] = $actionDateFrom = substr($actionDateFrom, 2); 

        //create teh headers
        $installHeaderRecord = '021001........G9710021'.$dateNow.$purgeDate.$generation_number.'18000180MAGTAPE   ';
        $userHeaderRecord = '04G971'.$dateNow.$purgeDate.$actionDateFrom.$actionDateTo.'000001'.$generation_number.'SAMEDAY   ';

        $installHeaderRecord = [$installHeaderRecord];
        $userHeaderRecord = [$userHeaderRecord];

        return [$installHeaderRecord,$userHeaderRecord];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // get all fields by action date - set by user on file-import blade
        $fields = export_field::all();
        foreach($fields as $field){
            $actionDateFrom = $field->dateField_1;
            $actionDateTo = $field->dateField_2;
        }

        // get days between each action date -> then get total rows for each action date
        $dateRange = [];
        $begin = new DateTime($actionDateFrom);
        $end = new DateTime($actionDateTo);

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
        
        foreach ($period as $dt) {
            array_push($dateRange, $dt->format('Y-m-d'));
        }

        $GLOBALS['actionDateTotal'] = [];
        // gets the total of all the amounts for each action date -> for the contra
        foreach($dateRange as $date){
            $query = DB::table('file_import_botswanas')
            ->where('ActionDate', '=', $date)
            ->sum('RecipientAmount');
        
            array_push($GLOBALS['actionDateTotal'], ['Date'=>$date, 'Amount'=>$query]);
        }
        
        // get all rows for thestandard transaction details
        $export = DB::table('file_import_botswanas')
        ->whereBetween('ActionDate', [$actionDateFrom, $actionDateTo])
        ->orderBy('ActionDate','asc')
        ->get();

        // calculate all amounts for every row, for the etire batch, which is the total for Total Contra Value
        $GLOBALS['totalContraAmount'] = DB::table('file_import_botswanas')
        ->whereBetween('ActionDate', [$actionDateFrom, $actionDateTo])
        ->sum('RecipientAmount');
       
        // set global values, ****** -> as I am unable to edit the next function, to pass values --- $array = $export->map(function ($export, $key) { ---
        $count = count($export) - 1;
        $GLOBALS['count'] = $count;
        $GLOBALS['sequenceNumber'] = 0;
        $GLOBALS['transactionCounter'] = 0;
        $GLOBALS['setDate'] = '';
        $GLOBALS['totalContra'] = count($GLOBALS['actionDateTotal']);

        $array = $export->map(function ($export, $key) { //$array = $export->map(function ($export) {
            //$sequenceNumber += $sequenceNumber;
            $GLOBALS['sequenceNumber'] = $GLOBALS['sequenceNumber'] + 1;
            $GLOBALS['transactionCounter'] = $GLOBALS['transactionCounter'] + 1;

            // most values have to be set to a specific length, below is an example of values set to a specific length
            $amount = $export->RecipientAmount;
            $amount = str_replace(".","",$amount);
            $str_length = strlen($amount);
            $zero = '00000000000';
            $amount = $zero . $amount;
            $amount = substr($amount, $str_length, 11);

            $actionDate = $export->ActionDate;
            $actionDate = explode("-", $actionDate);
            $actionDate = implode("", $actionDate);
            $actionDate = substr($actionDate, 2); 

            $recipientSurname_Initials = $export->RecipientAccountHolderSurname.' '.$export->RecipientAccountHolderInitials;
            $str_length = strlen($recipientSurname_Initials);
            $spaces = '               ';
            $recipientSurname_Initials = $spaces . $recipientSurname_Initials;
            $recipientSurname_Initials = substr($recipientSurname_Initials, $str_length, 15);

            $RecipientNonStandardAccountNumber = $export->RecipientNonStandardAccountNumber;
            $str_length = strlen($RecipientNonStandardAccountNumber);
            $zero = '00000000000000000000';
            $RecipientNonStandardAccountNumber = $zero . $RecipientNonStandardAccountNumber;
            $RecipientNonStandardAccountNumber = substr($RecipientNonStandardAccountNumber, $str_length, 20);

            $std_transaction_record = [
                '502506450200076260G971'.$GLOBALS['sequenceNumber'].$export->BranchCode.$export->RecipientAccountNumber.$export->RecipientAccountType.$amount.$export->RecipientAccountType.$actionDate
                .'210000LEGAL EXPE '.$export->PolicyNumber.' '.$recipientSurname_Initials.'               '.$export->RecipientNonStandardAccountNumber
                .'               21            '];

            // this will be blank on 1st run, set the date to the current action date
            if($GLOBALS['setDate'] == ''){
                // no return, just set the running date
                $GLOBALS['setDate'] = $export->ActionDate;
            }

            // if the current row action date is not the set date (above), then run a contra, and set the action date to the $GLOBALS['setDate']
            if($GLOBALS['setDate'] != $export->ActionDate){
                // search for current date set in array $GLOBALS['actionDateTotal'], get the key of the value found, use the key, use key to return the amounts value, this amount value is the sum of all the amounts for the action date
                $key = array_search($GLOBALS['setDate'], array_column($GLOBALS['actionDateTotal'], 'Date'));
                $amount = $GLOBALS['actionDateTotal'][$key]['Amount'];
                
                // remove the dot from amount, and then esure the amount value length is 11 characters, with leading zero's
                $amount = str_replace(".","",$amount);
                $str_length = strlen($amount);
                $zero = '00000000000';
                $amount = $zero . $amount;
                $amount = substr($amount, $str_length, 11);

                $setDate = $GLOBALS['setDate'];
                $setDate = explode("-", $setDate);
                $setDate = implode("", $setDate);
                $setDate = substr($setDate, 2); 
                
                // increment $GLOBALS['sequenceNumber'] for the Contra Record
                $GLOBALS['sequenceNumber'] = $GLOBALS['sequenceNumber'] + 1;

                $contra_record = [
                    '52250645020000762604055'.$GLOBALS['sequenceNumber'].'250645020000762601'.$amount.$setDate
                    .'100000LEGAL EXPECONTRA    NOR'.$GLOBALS['sequenceNumber'].'LEGAL EXPENSES INSURANCE SOUTH                                                  '];
                
                // set date to next action date
                $GLOBALS['setDate'] = $export->ActionDate;

                return [$contra_record, $std_transaction_record];
            }

            // runs last 2 rows,  'User Trailer Record' and Instll Trailer Record    048786
            if($key == $GLOBALS['count']){
                // search for current date set in array, return the array key, use key to return sum of amounts array value
                $key = array_search($GLOBALS['setDate'], array_column($GLOBALS['actionDateTotal'], 'Date'));
                $amount = $GLOBALS['actionDateTotal'][$key]['Amount'];
                
                // remove the dot, and then esure the amount value length is 11 characters, with leading zero's
                $amount = str_replace(".","",$amount);
                $str_length = strlen($amount);
                $zero = '00000000000';
                $amount = $zero . $amount;
                $amount = substr($amount, $str_length, 11);

                $setDate = $GLOBALS['setDate'];
                $setDate = explode("-", $setDate);
                $setDate = implode("", $setDate);
                $setDate = substr($setDate, 2); 

                $contra_record = [
                    '52250645020000762604055'.$GLOBALS['sequenceNumber'].'250645020000762601'.$amount.$setDate
                    .'100000LEGAL EXPECONTRA    NOR'.$GLOBALS['sequenceNumber'].' LEGAL EXPENSES INSURANCE SOUTH                                                  '];
                
                $amount = str_replace(".","",$GLOBALS['totalContraAmount']);
                $str_length = strlen($amount);
                $zero = '000000000000';
                $amount = $zero . $amount;
                $amount = substr($amount, $str_length, 12);

                $transactionCounter = $GLOBALS['transactionCounter'];
                $str_length = strlen($transactionCounter);
                $zero = '000000';
                $transactionCounter = $zero . $transactionCounter;
                $transactionCounter = substr($transactionCounter, $str_length, 6);

                $totalContra = $GLOBALS['totalContra'];
                $str_length = strlen($totalContra);
                $zero = '000000';
                $totalContra = $zero . $totalContra;
                $totalContra = substr($totalContra, $str_length, 6);

                $user_trailer_record = [
                    '92G971'.$GLOBALS['sequenceNumber'].$GLOBALS['actionDateFirst'].$GLOBALS['purgeDate'].$transactionCounter.'000000'.$totalContra.$amount.$amount.'#Hash#Total#'];

                $install_trailer_record = [
                    '941001........G9710021'.$GLOBALS['dateNow'].$GLOBALS['purgeDate'].$GLOBALS['generation_number'].'18000180MAGTAPE   '];

                return [$std_transaction_record, $contra_record, $user_trailer_record, $install_trailer_record];
            }
            return [$std_transaction_record]; // default row return
        });
        DB::table('file_import_botswanas')->whereBetween('ActionDate', [$actionDateFrom, $actionDateTo])->delete();
        /*
        // delete the exported rows from table
        DB::table('file_import_botswanas')->whereBetween('ActionDate', [$actionDateFrom, $actionDateTo])->delete();
        // move all exported rows to archive for reference
        foreach($export as $value){
            //dd($value->RecipientAccountHolderName);
            DB::table('file_import_botswana_archives')->insert(
                array(
                    'RecipientAccountHolderName' => $value->RecipientAccountHolderName,
                    'RecipientAccountHolderSurname' => $value->RecipientAccountHolderSurname,
                    'RecipientAccountHolderInitials' => $value->RecipientAccountHolderInitials,
                    'RecipientID' => $value->RecipientID,
                    'BranchCode' => $value->BranchCode,
                    'RecipientAccountNumber' => $value->RecipientAccountNumber,
                    'RecipientNonStandardAccountNumber' => $value->RecipientNonStandardAccountNumber,
                    'RecipientAccountType' => $value->RecipientAccountType,
                    'AccountReference' => $value->AccountReference,
                    'RecipientAmount' => $value->RecipientAmount,
                    'PolicyNumber' => $value->PolicyNumber,
                    'ActionDate' => $value->ActionDate,
                    'Guid' => $value->Guid,
                )
            );
        } */
        return $array;
    }
}





















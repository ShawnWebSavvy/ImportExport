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

        // calculate the total amounts for credit transactions (transactionType = '9999')
        // get all rows for thestandard transaction details (transactionType = '0000')
        // debit
        $GLOBALS['debitTotal'] = DB::table('mercantile_transactions')
        ->whereBetween('ActionDate', [$actionDateFrom, $actionDateTo])
        ->where('TransactionType', '=', '0000')
        ->sum('Amount');
        // credit
        $GLOBALS['creditTotal'] = DB::table('mercantile_transactions')
        ->whereBetween('ActionDate', [$actionDateFrom, $actionDateTo])
        ->where('TransactionType', '=', '9999')
        ->sum('Amount');

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
        $GLOBALS['credit_transaction'] = 0;
        $GLOBALS['debit_transaction'] = 0;
        $GLOBALS['actionDateFrom'] = $actionDateFrom;
        $GLOBALS['actionDateTo'] = $actionDateTo;
        $GLOBALS['hashTotalAccountNumber'] = 0;
        $GLOBALS['run'] = 0;

        $actionDateFrom = explode("-", $actionDateFrom);
        $actionDateFrom = implode("", $actionDateFrom);
        $GLOBALS['actionDateFrom'] = $actionDateFrom = substr($actionDateFrom, 2); 

        $actionDateTo = explode("-", $actionDateTo);
        $actionDateTo = implode("", $actionDateTo);
        $GLOBALS['actionDateTo'] = $actionDateTo = $purgeDate = substr($actionDateTo, 2); 

        $array = $export->map(function ($export, $key) { //$array = $export->map(function ($export) {
            $GLOBALS['run'] = $GLOBALS['run'] + 1;
            // set transaction type
            $TransactionType = $export->TransactionType;
            if($TransactionType == '0000'){
                $TransactionType = 'T';
                $GLOBALS['debit_transaction'] = $GLOBALS['debit_transaction'] +1;
            }else if($TransactionType == '9999'){
                $TransactionType = 'C';
                $GLOBALS['credit_transaction'] = $GLOBALS['credit_transaction'] +1;
            }else{
                // incase both dont match, then set default value
                $TransactionType = '0';
            }
            
            $UserBranchCode = $export->UserBranchCode;
            $UserAccountNumber = $export->UserAccountNumber;
            //$AccountType = $export->; ||** No reference to 'account type' in import **||
            $Amount = $export->Amount;
            //$Company = $export->; ||** No reference to 'Company' in import **||
            $PolicyNumber = $export->PolicyNumber;
            $AccountHolderName = $export->AccountHolderFullName;
            // calculate hash total of all account numbers
            $GLOBALS['hashTotalAccountNumber'] = $GLOBALS['hashTotalAccountNumber'] + $UserAccountNumber;
            $ActionDate = $export->ActionDate;
            //$CDV_Mode = $export->; ||** No reference to 'CDV_Mode' in import **||
            //$EntryClass = $export->;

            // setting hard values for unknowns
            $AccountType = '0';
            $Company = 'Company';
            $CDV_Mode = '0';
            $EntryClass = '32'; // Documentation -> 2 day debits = 32

            // VVV set the specif length of each variable VVV //
            $Amount = str_replace(".","",$Amount);
            $str_length = strlen($Amount);
            $zero = '00000000000';
            $Amount = $zero . $Amount;
            $Amount = substr($Amount, $str_length, 11);

            $debitTotal = $GLOBALS['debitTotal'];
            $debitTotal = str_replace(".0","",$debitTotal);
            $str_length = strlen($debitTotal);
            $zero = '000000000000';
            $debitTotal = $zero . $debitTotal;
            $debitTotal = substr($debitTotal, $str_length, 12);

            $creditTotal = $GLOBALS['creditTotal'];
            $creditTotal = str_replace(".0","",$creditTotal);
            $str_length = strlen($creditTotal);
            $zero = '000000000000';
            $creditTotal = $zero . $creditTotal;
            $creditTotal = substr($creditTotal, $str_length, 12);

            $spaces = '          '; // 10
            $Company = $Company . $spaces;
            $Company = substr($Company, 0, 10);

            $ClientIdentifier = $PolicyNumber;
            $spaces = '                    '; // 20
            $ClientIdentifier = $ClientIdentifier . $spaces;
            $ClientIdentifier = substr($ClientIdentifier, 0, 20);

            $spaces = '                              '; // 30
            $AccountHolderName = $AccountHolderName . $spaces;
            $AccountHolderName = substr($AccountHolderName, 0, 30);
            // ^^^ set the specif length of each variable ^^^ //

            // build standard transaction row
            $std_transaction_record = [
                $TransactionType.$UserBranchCode.$UserAccountNumber.$AccountType.
                $Amount.$Company.$ClientIdentifier.$AccountHolderName.
                $ActionDate.$CDV_Mode.$EntryClass 
            ];
            
            if($key == $GLOBALS['count']){
                $record_id = 'Z92';
                $user_code = 'Test';
                $filler = '000001';
                $sequenceNumber = $GLOBALS['count'] +1;
                // sequance number must be 6 digits
                $str_length = strlen($sequenceNumber);
                $zero = '000000';
                $sequenceNumber = $zero . $sequenceNumber;
                $sequenceNumber = substr($sequenceNumber, $str_length, 6);

                // debit and credit transaction  total count must be 6 digits
                $str_length = strlen($GLOBALS['debit_transaction']);
                $zero = '000000';
                $debit_transaction = $zero . $GLOBALS['debit_transaction'];
                $debit_transaction = substr($debit_transaction, $str_length, 6);
                // debit and credit transaction  total count must be 6 digits
                $str_length = strlen($GLOBALS['credit_transaction']);
                $zero = '000000';
                $credit_transaction = $zero . $GLOBALS['credit_transaction'];
                $credit_transaction = substr($credit_transaction, $str_length, 6);

                $hashTotal = $GLOBALS['hashTotalAccountNumber'];
                $str_length = strlen($hashTotal);
                $zero = '0000000000000000';
                $hashTotal = $zero . $hashTotal;
                $hashTotal = substr($hashTotal, $str_length, 16);

                // build trailer row
                $trailer_record = [
                    $record_id.$user_code.$filler.$sequenceNumber.
                    $GLOBALS['actionDateFrom'].$GLOBALS['actionDateTo'].
                    $debit_transaction.$credit_transaction.$filler.
                    $debitTotal.$creditTotal.$hashTotal
                ];
                // output standard transaction row, and trailer row
                return [$std_transaction_record, $trailer_record]; 
            }
            return [$std_transaction_record]; 
        });
        // delete and push to archive
        DB::table('mercantile_transactions')
        ->join('mercantile_user_banks', 'mercantile_transactions.policy_id', '=', 'mercantile_user_banks.policy_id')
        ->whereBetween('ActionDate', [$actionDateFrom, $actionDateTo])
        ->where('mercantile_user_banks.UserBankType', '=', 'Capitec')
        ->delete();

        foreach($export as $value){
            DB::table('mercantile_capitec_transactions_archives')->insert(
                array(
                    'RecordIdentifier' => $value->RecordIdentifier,
                    'PaymentReference' => $value->PaymentReference,
                    'Amount' => $value->Amount,
                    'ActionDate' => $value->ActionDate,
                    'TransactionUniqueID' => $value->TransactionUniqueID,
                    'StatementReference' => $value->StatementReference,
                    'CycleDate' => $value->CycleDate,
                    'TransactionType' => $value->TransactionType,
                    'ServiceType' => $value->ServiceType,
                    'OriginalPaymentReference' => $value->OriginalPaymentReference,
                    'EntryClass' => $value->EntryClass,
                    'NominatedAccountReference' => $value->NominatedAccountReference,
                    'BDF_Indicator' => $value->BDF_Indicator,
                    'policy_id' => $value->policy_id,
                )
            );
        } 
        return $array;
    }
}

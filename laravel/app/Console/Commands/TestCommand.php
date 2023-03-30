<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use File;

class TestCommand extends Command implements ShouldQueue, ShouldBeUnique
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:testEmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'simple test - send an email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //get generation number & increment
        $query = DB::table('generation_numbers')->orderBy('id', 'desc')
            ->where('bank', 'Capitec')
            ->first();

        if(!$query){$generation_number = 0001;} else {
            // incremnt the generation number
            $generation_number = $query->generation_number_capitec + 1;
        }
        //if = 10000, then reset, as it can only be 4 digits
        if($generation_number > 9999){
            $generation_number = 1;
        }
        // after increment, number can be less than 4 digits, so check and add back the 0's
        $str_length = strlen($generation_number);
        $zero = '0000';
        $generation_number = $zero . $generation_number;
        $generation_number = substr($generation_number, $str_length, 4);

        DB::table('generation_numbers')
            ->where('bank', 'Capitec',)
            ->delete();
        // insert generation number, to keep refernce
        DB::table('generation_numbers')->insert([
            'generation_number_capitec' => $generation_number,
            'bank' => 'Capitec',]
        );

        // retrieve current capitec control data array
        $_SESSION['capitecControlArray'] = [];
        $capitec_accounts1000s = DB::table('capitec_accounts1000s')
            ->select('PolicyNumber')
            ->join('mercantile_user_banks', 'capitec_accounts1000s.PolicyNumber', '=', 'mercantile_user_banks.policy_id')
            ->where('mercantile_user_banks.UserBankType', '=', 'Capitec')
            ->get(); 
        // fix for no entries - Undefined offset: 0 

        foreach($capitec_accounts1000s as $control){
            array_push($_SESSION['capitecControlArray'], $control->PolicyNumber);
        }
        $_SESSION['capitecArray'] = [];
        $_SESSION['nedbankArray'] = [];

        $deductionsArray = DB::table('deductions')
        ->orderBy('id')
        ->chunk(4096, function ($deductions) {
            foreach ($deductions as $deduction) {
                $RecordIdentifier = substr($deduction->row, 0, 2);
                if($RecordIdentifier == '02'){
                    // build 2 arrays
                    $DestinationBranchCode = substr($deduction->row, 52, 6);
                    $BankType = 'Capitec';
                    if($DestinationBranchCode != '470010'){$BankType = 'Nedbank';}
                    if($BankType == 'Capitec'){
                        if(in_array($deduction->PolicyNumber, $_SESSION['capitecControlArray'])){
                            //array_push($capitecArray, $deduction->row);
                            array_push($_SESSION['capitecArray'], $deduction->row);
                        } else {
                            // if capitec, but not control, insert into Nedbank array
                            array_push($_SESSION['nedbankArray'], $deduction->row);
                        }
                    } else {
                        //array_push($nedbankArray, $deduction->row);
                        array_push($_SESSION['nedbankArray'], $deduction->row);
                    }
                } else if ($RecordIdentifier == '01'){
                    // insert header
                    DB::table('mercantile_headers')->delete();
                    DB::table('mercantile_headers')->insert(array('HeaderRow' => $deduction->row));
                }
            }
        });
        // move current files to archive, then create new files for download
        $files = Storage::files("downloads/mercantile/current/");
        foreach ($files as $value) {
            $file_name = str_replace('downloads/mercantile/current/','', $value);
            File::move(storage_path('app/downloads/mercantile/current/'.$file_name), storage_path('app/downloads/mercantile/archive/'.$file_name));
        }

        // build header for each output file
        $first_row = DB::table('deductions')
        ->skip(1)
        ->first();

        $last_row = DB::table('deductions')
        ->orderBy('id', 'desc')
        ->skip(1)
        ->first();

        $actionDateFrom = substr($first_row->row, 86, 8);
        $actionDateFrom = explode("-", $actionDateFrom);
        $actionDateFrom = implode("", $actionDateFrom);
        //$actionDateFrom = substr($actionDateFrom, 2, 8);

        $actionDateTo = substr($last_row->row, 86, 8);
        $actionDateTo = explode("-", $actionDateTo);
        $actionDateTo = implode("", $actionDateTo);
        //$actionDateTo = substr($actionDateTo, 2, 8);

        $headerCapitec = 'H04Test'.$actionDateTo.$actionDateTo.$actionDateFrom.$actionDateTo.'000001'.$generation_number.'TWO DAY   '.'01';
        Storage::put('downloads/mercantile/current/MercantileCapitec_'.date("Y_m_d").'.txt', $headerCapitec);

        $headerNedbank = DB::table('mercantile_headers')->first();
        Storage::put('downloads/mercantile/current/MercantileNedbank_'.date("Y_m_d").'.txt', $headerNedbank->HeaderRow);

        // run each array into the text file
        $totalCapitec = count($_SESSION['capitecArray']);
        $accountNumberHash = $creditTransactionsTotalRows = $debitTransactionsTotalRows = $totalCredit = $totalDebit = 0;
        if($totalCapitec > 1){
            foreach ($_SESSION['capitecArray'] as $row) {
                $AccountHolderFullName = substr($row, 124, 30);
                $amount = substr($row, 75, 11);
                $PolicyNumber = substr($row, 104, 14);
                $destinationBranchCode = substr($row, 52, 6);
                $accountNumber = substr($row, 58, 16);
                $transactionType = substr($row, 154, 4);

                // total all account numbers
                $accountNumberHash = $accountNumberHash + $accountNumber;
                // then append row to text file

                // check if credit or debit // count total credit(9999) / debit(0000) transactions // total for credit(9999) / debit(0000) transactions
                if($transactionType == '9999'){
                    $creditTransactionsTotalRows ++;
                    $totalCredit += $amount;
                    $transactionTypeSymbol = 'C';
                } else {
                    $debitTransactionsTotalRows ++;
                    $totalDebit += $amount;
                    $transactionTypeSymbol = 'T';
                }

                $AccountType = '1';
                $Company = 'SCORPION  ';
                $CDV_Mode = '0';
                $EntryClass = '32'; 
                $branchCode = '470010';
                $amount = str_pad($amount, 11, '0', STR_PAD_RIGHT);
                $ClientIdentifier = str_pad($PolicyNumber, 20, ' ', STR_PAD_RIGHT);
                $accountNumber = substr($accountNumber, 3, 13);

                $std_transaction_record = 
                $transactionTypeSymbol.$branchCode.$accountNumber.$AccountType.
                $amount.$Company.$ClientIdentifier.$AccountHolderFullName.
                $actionDateFrom.$CDV_Mode.$EntryClass;

                Storage::append('downloads/mercantile/current//MercantileCapitec_'.date("Y_m_d").'.txt', $std_transaction_record);
            }
        }
        // finish with Capitec footer
        $actionDateFrom = substr($actionDateFrom, 2, 6);
        $actionDateTo = substr($actionDateTo, 2, 6);

        $record_id = 'Z92';
        $user_code = 'Test';
        $filler = '000001';
        $sequenceNumber = $totalCapitec;
        // sequance number must be 6 digits
        $str_length = strlen($sequenceNumber);
        $zero = '000000';
        $sequenceNumber = $zero . $sequenceNumber;
        $sequenceNumber = substr($sequenceNumber, $str_length, 6);

        // debit and credit transaction  total count must be 6 digits
        $str_length = strlen($totalDebit);
        $zero = '000000';
        $capitecDebit = $zero . $totalDebit;
        $capitecDebit = substr($capitecDebit, $str_length, 6);
        // debit and credit transaction  total count must be 6 digits
        $str_length = strlen($totalCredit);
        $zero = '000000';
        $capitecCredit = $zero . $totalCredit;
        $capitecCredit = substr($capitecCredit, $str_length, 6);

        $hashTotal = $accountNumberHash;
        $str_length = strlen($hashTotal);
        $zero = '0000000000000000';
        $hashTotal = $zero . $hashTotal;
        $hashTotal = substr($hashTotal, $str_length, 16);

        // build Capitec trailer row
        $trailer_record = 
        $record_id.$user_code.$filler.$sequenceNumber.
        $actionDateFrom.$actionDateTo.
        $debitTransactionsTotalRows.$creditTransactionsTotalRows.$filler.
        $capitecDebit.$capitecCredit.$hashTotal;

        // 
        // Z92Test 000001 0006132211012211056130 000001 5403000000000000846197486320
        //dd($trailer_record);

        Storage::append('downloads/mercantile/current//MercantileCapitec_'.date("Y_m_d").'.txt', $trailer_record );
        Storage::append('downloads/mercantile/current//MercantileCapitec_'.date("Y_m_d").'.txt', '' );

        $totalNedbank = count($_SESSION['nedbankArray']);
        $accountNumberHash = $creditTransactionsTotal = $debitTransactionsTotal = $totalCredit = $totalDebit = 0;
        foreach ($_SESSION['nedbankArray'] as $row) {
            $amount = substr($row, 74, 12);
            $destinationBranchCode = substr($row, 52, 6);
            $accountNumber = substr($row, 58, 16);
            $transactionType = substr($row, 154, 4);
            // check if credit or debit
            // count total credit(9999) / debit(0000) transactions
            // total for credit(9999) / debit(0000) transactions
            if($destinationBranchCode == '9999'){
                $creditTransactionsTotal ++;
                $totalCredit += $amount;
            } else {
                // debit (0000)
                $debitTransactionsTotal ++;
                $totalDebit += $amount;
            }
            // total all account numbers
            $accountNumberHash = $accountNumberHash + $accountNumber;
            // then append row to text file
            Storage::append('downloads/mercantile/current//MercantileNedbank_'.date("Y_m_d").'.txt', $row);
        }
        $totalNedbankAmount = $totalCredit + $totalDebit;
        // finish with Nedbank footer
        $str_length = strlen($totalNedbank);
        $zero = '00000000';
        $totalNedbank = $zero . $totalNedbank;
        $totalNedbank = substr($totalNedbank, $str_length, 8);

        $transactionTotalAmount = str_replace(".0","",$totalNedbankAmount);
        $str_length = strlen($transactionTotalAmount);
        $zero = '000000000000000000';
        $transactionTotalAmount = $zero . $transactionTotalAmount;
        $transactionTotalAmount = substr($transactionTotalAmount, $str_length, 18);

        // nedbank footer to file
        $trailer = '03'.$totalNedbank.$transactionTotalAmount.'                                                                                                                                                                                                                                                                            ';
        Storage::append('downloads/mercantile/current/MercantileNedbank_'.date("Y_m_d").'.txt', $trailer );
        Storage::append('downloads/mercantile/current/MercantileNedbank_'.date("Y_m_d").'.txt', '' );

        //$user = Auth::user();
        //$email = $user->email;
        //Mail::to($user->email)->send(new NotificationEmail());
        //Mail::to($email)->send(new NotificationEmail());

        Mail::to('shawnw@leza.co.za')->send(new NotificationEmail());
        //Mail::to('markw@leza.co.za')->send(new NotificationEmail());


        // delete first and last row, as these are the header and footer, not transactions.
        DB::table('deductions')
        ->orderBy('id', 'asc')
        ->limit(1)
        ->delete();

        DB::table('deductions')
        ->orderBy('id', 'desc')
        ->limit(1)
        ->delete();

        // insert into tables for record keeping
        $transactions = DB::table('deductions')
        ->select('row')
        ->orderBy('id','asc')
        ->get();

        foreach($transactions as $transaction){
            $RecordIdentifier = substr($transaction->row, 0, 2);
            $AccountHolderFullName = substr($transaction->row, 124, 30);
            $explode = explode(" ", $AccountHolderFullName);
            if(count($explode) > 1){
                $AccountHolderSurame = $explode[0];
                $AccountHolderInitials = trim($explode[1]);
            } else {
                $AccountHolderSurame = $AccountHolderInitials = '';
            }
            $DestinationAccountNumber = substr($transaction->row, 58, 16);
            $DestinationBranchCode = substr($transaction->row, 52, 6);
            $PaymentReference = substr($transaction->row, 18, 34);
            $TransactionOrder = substr($transaction->row, 24, 10);
            $Amount = substr($transaction->row, 74, 12);
            $ActionDate = substr($transaction->row, 86, 8);
            $TransactionUniqueID = substr($transaction->row, 94, 30);
            $StatementReference = substr($transaction->row, 94, 10);
            $PolicyNumber = substr($transaction->row, 104, 14);
            $CycleDate = substr($transaction->row, 118, 6);
            $TransactionType = substr($transaction->row, 154, 4);
            $ClientType = substr($transaction->row, 158, 2);
            $ChargesAccountNumber = substr($transaction->row, 160, 16);
            $ServiceType = substr($transaction->row, 176, 2);
            $OriginalPaymentReference = substr($transaction->row, 178, 34);
            $EntryClass = substr($transaction->row, 212, 2);
            $NominatedAccountReference = substr($transaction->row, 214, 30);
            $NominatedAccountNumber = substr($transaction->row, 2, 16);
            $BDF_Indicator = substr($transaction->row, 244, 1);

            $BankType = 'Capitec';
            if($DestinationBranchCode != '470010')
            {
                $BankType = 'Nedbank';

                DB::table('mercantile_nedbank_transactions_archives')->insert(
                    array(
                        'RecordIdentifier' => $RecordIdentifier,
                        'PaymentReference' => $PaymentReference,
                        'Amount' => $Amount,
                        'ActionDate' => $ActionDate,
                        'TransactionUniqueID' => $TransactionUniqueID,
                        'StatementReference' => $StatementReference,
                        'CycleDate' => $CycleDate,
                        'TransactionType' => $TransactionType,
                        'TransactionOrder' => $TransactionOrder,
                        'ServiceType' => $ServiceType,
                        'OriginalPaymentReference' => $OriginalPaymentReference,
                        'EntryClass' => $EntryClass,
                        'NominatedAccountReference' => $NominatedAccountReference,
                        'BDF_Indicator' => $BDF_Indicator,
                        'policy_id' => $PolicyNumber,
                        'Processed' => '0',
                    )
                );
            } else {
                DB::table('mercantile_capitec_transactions_archives')->insert(
                    array(
                        'RecordIdentifier' => $RecordIdentifier,
                        'PaymentReference' => $PaymentReference,
                        'Amount' => $Amount,
                        'ActionDate' => $ActionDate,
                        'TransactionUniqueID' => $TransactionUniqueID,
                        'StatementReference' => $StatementReference,
                        'CycleDate' => $CycleDate,
                        'TransactionType' => $TransactionType,
                        'TransactionOrder' => $TransactionOrder,
                        'ServiceType' => $ServiceType,
                        'OriginalPaymentReference' => $OriginalPaymentReference,
                        'EntryClass' => $EntryClass,
                        'NominatedAccountReference' => $NominatedAccountReference,
                        'BDF_Indicator' => $BDF_Indicator,
                        'policy_id' => $PolicyNumber,
                        'Processed' => '0',
                    )
                );
            }

            $policy = DB::table('mercantile_user_policies')->where('PolicyNumber',$PolicyNumber)->first();
                if($policy){
                    /* policy number exists, update banking details */
                    DB::table('mercantile_user_banks')
                    ->where('policy_id', $PolicyNumber)
                    ->update([
                        'UserAccountNumber' => $DestinationAccountNumber,
                        'UserBranchCode' => $DestinationBranchCode,
                        'UserBankType' => $BankType,
                    ]);
                } else { /* policy does not exist -> insert all fields */
                    
                    DB::table('mercantile_user_policies')->insert(
                        array(
                            'PolicyNumber' => $PolicyNumber,
                            'dummy_data_Capitec_active' => 0,
                        )
                    );
                    
                    DB::table('mercantile_users')->insert(
                        array(
                            'AccountHolderFullName' => $AccountHolderFullName,
                            'AccountHolderSurame' => $AccountHolderSurame,
                            'AccountHolderInitials' => $AccountHolderInitials,
                            'ClientType' => $ClientType,
                            'policy_id' => $PolicyNumber,
                        )
                    );

                    DB::table('mercantile_user_banks')->insert(
                        array(
                            'UserAccountNumber' => $DestinationAccountNumber,
                            'UserBranchCode' => $DestinationBranchCode,
                            'UserBankType' => $BankType,
                            'policy_id' => $PolicyNumber,
                        )
                    );
                }

            DB::table('mercantile_transactions')->insert(
                array(
                    'RecordIdentifier' => $RecordIdentifier,
                    'PaymentReference' => $PaymentReference,
                    'Amount' => $Amount,
                    'ActionDate' => $ActionDate,
                    'TransactionUniqueID' => $TransactionUniqueID,
                    'StatementReference' => $StatementReference,
                    'CycleDate' => $CycleDate,
                    'TransactionType' => $TransactionType,
                    'TransactionOrder' => $TransactionOrder,
                    'ServiceType' => $ServiceType,
                    'OriginalPaymentReference' => $OriginalPaymentReference,
                    'EntryClass' => $EntryClass,
                    'NominatedAccountReference' => $NominatedAccountReference,
                    'BDF_Indicator' => $BDF_Indicator,
                    'policy_id' => $PolicyNumber,
                    'Processed' => '0',
                )
            );
        }
        // delete deductions table, for a new clean import
        //DB::table('deductions')->delete();
    }
}

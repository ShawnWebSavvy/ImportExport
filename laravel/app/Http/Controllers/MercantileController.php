<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MercantileUser;
use App\Models\MercantileTransaction;
use App\Models\MercantileUserBank;
use App\Models\MercantileUserPolicy;
use App\Models\MercantileTransactionRejections;
use App\Models\MercantileNominatedBank;
use App\Models\MercantileHeader;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\SimpleExcel\SimpleExcelReader;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Illuminate\Support\Facades\DB;
use App\Exports\MercantileCapitecExport;
use App\Exports\MercantileNedbankExport;
use Shuchkin\SimpleXLSX;

class MercantileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fileExportMercantileIndex()
    {        
        return view('Mercantile.file-export-mercantile-index', [
            'capitecQuery' => DB::table('mercantile_user_policies')
            ->join('mercantile_transactions', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_transactions.policy_id')
            ->join('mercantile_users', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_users.policy_id')
            ->join('mercantile_user_banks', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_user_banks.policy_id')
            ->where('mercantile_user_banks.UserBankType', '=', 'Capitec')
            ->where('mercantile_user_policies.dummy_data_Capitec_active', '=', '1')
            //->where('mercantile_transactions.Processed', '=', '0')
            ->paginate(20),

            'nedbankQuery' => DB::table('mercantile_user_policies')
            ->join('mercantile_transactions', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_transactions.policy_id')
            ->join('mercantile_users', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_users.policy_id')
            ->join('mercantile_user_banks', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_user_banks.policy_id')
            ->where('mercantile_user_banks.UserBankType', '=', 'Nedbank')
            //->where('mercantile_transactions.Processed', '=', '0')
            ->paginate(20),
        ]);
    }

    public function fileExportMercantileNedbank(Request $request)
    {
        $export = DB::table('mercantile_user_policies')
            ->join('mercantile_transactions', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_transactions.policy_id')
            ->join('mercantile_users', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_users.policy_id')
            ->join('mercantile_user_banks', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_user_banks.policy_id')
            ->where('mercantile_user_banks.UserBankType', '=', 'Nedbank')
            ->orderBy('ActionDate','asc')
            ->get();

        $transactionTotalCount = count($export);
        
        $transactionTotalAmount = DB::table('mercantile_transactions')
        ->join('mercantile_user_banks', 'mercantile_transactions.policy_id', '=', 'mercantile_user_banks.policy_id')
        ->where('mercantile_user_banks.UserBankType', '=', 'Nedbank')
        ->sum('Amount');
        
        $header = DB::table('mercantile_headers')->first();
        
        $myfile = fopen("MercantileNedbank.txt", "w") or die("Unable to open file!");
        fwrite($myfile, $header->HeaderRow);
        fclose($myfile);
        
        $str_length = strlen($transactionTotalCount);
        $zero = '00000000';
        $transactionTotalCount = $zero . $transactionTotalCount;
        $transactionTotalCount = substr($transactionTotalCount, $str_length, 8);

        $transactionTotalAmount = str_replace(".0","",$transactionTotalAmount);
        $str_length = strlen($transactionTotalAmount);
        $zero = '000000000000000000';
        $transactionTotalAmount = $zero . $transactionTotalAmount;
        $transactionTotalAmount = substr($transactionTotalAmount, $str_length, 18);

        $trailer = '03'.$transactionTotalCount.$transactionTotalAmount.'                                                                                                                                                                                                                                                                            ';
        //dd($actionDate);

        //$header->HeaderRow
        $nominatedAccountNumber = substr($header->HeaderRow, 2, 16);
        //dd($nominatedAccountNumber);

        $myfile = fopen("MercantileNedbank.txt", "a") or die("Unable to open file!");
        foreach($export as $key => $v){
            $actionDate = implode("", explode("-", $v->ActionDate));
            $spaces = ' '; 
            $BDF_Indicator = $v->BDF_Indicator . $spaces;
            $BDF_Indicator = substr($BDF_Indicator, 0, 1);

            $row = $v->RecordIdentifier.$nominatedAccountNumber.$v->PaymentReference.$v->UserBranchCode.$v->UserAccountNumber.
            $v->Amount.$actionDate.$v->TransactionUniqueID.$v->AccountHolderFullName.$v->TransactionType.
            $v->ClientType.$nominatedAccountNumber.$v->ServiceType.$v->PaymentReference.$v->EntryClass.
            $v->NominatedAccountReference.$BDF_Indicator.
            '                                                                           '."\n";
            fwrite($myfile, $row);
        }
        fwrite($myfile, $trailer);
        fclose($myfile);

        // delete nedbank transactions
        DB::table('mercantile_transactions')
        ->join('mercantile_user_banks', 'mercantile_transactions.policy_id', '=', 'mercantile_user_banks.policy_id')
        ->where('mercantile_user_banks.UserBankType', '=', 'Nedbank')
        ->delete(); 

        DB::table('mercantile_transactions')
        ->join('mercantile_user_banks', 'mercantile_transactions.policy_id', '=', 'mercantile_user_banks.policy_id')
        ->where('mercantile_user_banks.UserBankType', '=', 'Capitec')
        ->delete(); 

        /*
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
        */
    }
    public function fileExportMercantileCapitec(Request $request)
    {
        $export = DB::table('mercantile_user_policies')
            ->join('mercantile_transactions', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_transactions.policy_id')
            ->join('mercantile_users', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_users.policy_id')
            ->join('mercantile_user_banks', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_user_banks.policy_id')
            ->where('mercantile_user_banks.UserBankType', '=', 'Capitec')
            ->where('mercantile_user_policies.dummy_data_Capitec_active', '=', '1')
            ->orderBy('ActionDate','asc')
            ->get();
        
        // debit total
        $debitTotal = DB::table('mercantile_transactions')
        ->join('mercantile_user_banks', 'mercantile_transactions.policy_id', '=', 'mercantile_user_banks.policy_id')
        ->join('mercantile_user_policies', 'mercantile_transactions.policy_id', '=', 'mercantile_user_policies.PolicyNumber')
        ->where('TransactionType', '=', '0000')
        ->where('mercantile_user_banks.UserBankType', '=', 'Capitec')
        ->where('mercantile_user_policies.dummy_data_Capitec_active', '=', '1')
        ->sum('Amount');
        // credit total
        $creditTotal = DB::table('mercantile_transactions')
        ->join('mercantile_user_banks', 'mercantile_transactions.policy_id', '=', 'mercantile_user_banks.policy_id')
        ->join('mercantile_user_policies', 'mercantile_transactions.policy_id', '=', 'mercantile_user_policies.PolicyNumber')
        ->where('TransactionType', '=', '9999')
        ->where('mercantile_user_banks.UserBankType', '=', 'Capitec')
        ->where('mercantile_user_policies.dummy_data_Capitec_active', '=', '1')
        ->sum('Amount');
        // Build Header
        // get Dates, now, from and to
        $dateNow = date('Y-m-d');
        $dateNow = explode("-", $dateNow);
        $dateNow = implode("", $dateNow);
        // Action Date from
        $first_row = $export[0];
        $actionDateFrom = $first_row->ActionDate;
        $actionDateFrom = explode("-", $actionDateFrom);
        $actionDateFrom = implode("", $actionDateFrom);
        // Action Date to
        $total_return = count($export);
        $data = $export[$total_return - 1];
        $actionDateTo = $data->ActionDate;
        $actionDateTo = explode("-", $actionDateTo);
        $actionDateTo = implode("", $actionDateTo);
        //dd($actionDateTo);
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
        DB::table('generation_numbers')->insert([
                'generation_number_botswana' => $generation_number,
                'bank' => 'Capitec',]
        );

        // write header to text file
        // use 'w' to clear text file, other inserts will b 'a'
        $header = 'H04Test'.$dateNow.$dateNow.$actionDateFrom.$actionDateTo.'000001'.$generation_number."\n";
        $myfile = fopen("MercantileCapitec.txt", "w") or die("Unable to open file!");
        fwrite($myfile, $header);
        fclose($myfile);

        // trailer settings
        $hashTotalAccountNumber = $credit_transaction = $debit_transaction = 0;
        // trailer settings

        $myfile = fopen("MercantileCapitec.txt", "a") or die("Unable to open file!");
        // build transaction records
        foreach($export as $key => $value){
            $TransactionType = $value->TransactionType;
            if($TransactionType == '0000'){
                $TransactionType = 'T';
                $debit_transaction = $debit_transaction +1;
            }else if($TransactionType == '9999'){
                $TransactionType = 'C';
                $credit_transaction = $credit_transaction +1;
            }else{
                // incase both dont match, then set default value
                $TransactionType = '0';
            }
            $UserBranchCode = $value->UserBranchCode;
            $UserAccountNumber = $value->UserAccountNumber;
            $Amount = $value->Amount;
            $PolicyNumber = $value->PolicyNumber;
            $AccountHolderName = $value->AccountHolderFullName;
            $ActionDate = $value->ActionDate;

            $AccountType = '0';
            $Company = 'Company';
            $CDV_Mode = '0';
            $EntryClass = '32'; 

            // VVV set the specif length of each variable VVV //
            $Amount = str_replace(".","",$Amount);
            $str_length = strlen($Amount);
            $zero = '00000000000';
            $Amount = $zero . $Amount;
            $Amount = substr($Amount, $str_length, 11);

            $debitTotal = str_replace(".0","",$debitTotal);
            $str_length = strlen($debitTotal);
            $zero = '000000000000';
            $debitTotal = $zero . $debitTotal;
            $debitTotal = substr($debitTotal, $str_length, 12);

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

            $hashTotalAccountNumber = $hashTotalAccountNumber + $UserAccountNumber;

            // build standard transaction row
            $std_transaction_record = 
                $TransactionType.$UserBranchCode.$UserAccountNumber.$AccountType.
                $Amount.$Company.$ClientIdentifier.$AccountHolderName.
                $ActionDate.$CDV_Mode.$EntryClass."\n";

            fwrite($myfile, $std_transaction_record);

            // trailer record
            if($key == $total_return -1){
                $record_id = 'Z92';
                $user_code = 'Test';
                $filler = '000001';
                $sequenceNumber = $total_return +1;
                // sequance number must be 6 digits
                $str_length = strlen($sequenceNumber);
                $zero = '000000';
                $sequenceNumber = $zero . $sequenceNumber;
                $sequenceNumber = substr($sequenceNumber, $str_length, 6);

                // debit and credit transaction  total count must be 6 digits
                $str_length = strlen($debit_transaction);
                $zero = '000000';
                $debit_transaction = $zero . $debit_transaction;
                $debit_transaction = substr($debit_transaction, $str_length, 6);
                // debit and credit transaction  total count must be 6 digits
                $str_length = strlen($credit_transaction);
                $zero = '000000';
                $credit_transaction = $zero . $credit_transaction;
                $credit_transaction = substr($credit_transaction, $str_length, 6);

                $hashTotal = $hashTotalAccountNumber;
                $str_length = strlen($hashTotal);
                $zero = '0000000000000000';
                $hashTotal = $zero . $hashTotal;
                $hashTotal = substr($hashTotal, $str_length, 16);

                // build trailer row
                $trailer_record = 
                    $record_id.$user_code.$filler.$sequenceNumber.
                    $actionDateFrom.$actionDateTo.
                    $debit_transaction.$credit_transaction.$filler.
                    $debitTotal.$creditTotal.$hashTotal;

                fwrite($myfile, $trailer_record);
            }
        } // foreach($export as $key => $value){
        
        // delete and push to archive
        DB::table('mercantile_transactions')
        ->join('mercantile_user_banks', 'mercantile_transactions.policy_id', '=', 'mercantile_user_banks.policy_id')
        ->join('mercantile_user_policies', 'mercantile_transactions.policy_id', '=', 'mercantile_user_policies.PolicyNumber')
        ->where('mercantile_user_banks.UserBankType', '=', 'Capitec')
        ->where('mercantile_user_policies.dummy_data_Capitec_active', '=', '1')
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
    }

    public function trialDataMercantileCapitec(){
        return view('Mercantile.capitec-test-stats', [
            'capitecQuery' => DB::table('mercantile_user_policies')
            ->join('mercantile_users', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_users.policy_id')
            ->join('mercantile_user_banks', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_user_banks.policy_id')
            ->where('mercantile_user_banks.UserBankType', '=', 'Capitec')
            ->where('mercantile_user_policies.dummy_data_Capitec_active', '=', '1')
            ->paginate(50),
        ]);
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\SimpleExcel\SimpleExcelReader;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Illuminate\Support\Facades\DB;
use App\Models\FileImportNamibia;
use App\Models\FileImportBotswana;
use App\Models\FileImportBotswanaRecordUserTrailer;
use App\Models\FileImportBotswanaRecordUserHeader;
use App\Models\FileImportBotswanaRecordTransaction;
use App\Models\FileImportBotswanaRecordInstallTrailer;
use App\Models\FileImportBotswanaRecordInstallHeader;
use App\Models\FileImportBotswanaRecordContra;
use App\Models\MercantileUser;
use App\Models\MercantileTransaction;
use App\Models\MercantileUserBank;
use App\Models\MercantileUserPolicy;
use App\Models\MercantileTransactionRejections;
use App\Models\MercantileNominatedBank;
use App\Models\MercantileHeader;
use App\Exports\NamibiaExport;
use App\Exports\BotswanaExport;
use App\Exports\BotswanaRecordUserTrailerExport;
use App\Exports\BotswanaRecordUserHeaderExport;
use App\Exports\BotswanaRecordTransactionExport;
use App\Exports\BotswanaRecordInstallTrailerExport;
use App\Exports\BotswanaRecordInstallHeaderExport;
use App\Exports\BotswanaRecordContraExport;
use App\Traits\Guid;

//require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Shuchkin\SimpleXLSX;

class FileController extends Controller
{
    use Guid;

    private $rows = 0;
    private $date;
    private $account_number;
    public $guid;

    public function fileImportIndex(){
       return view('FileImport.file-import');
    }

    public function fileUpload(Request $request) {
        // check if file uploaded
        if (!$request->hasFile('file')) {
            return view('FileImport.file-import')->withErrors(['msg' => 'Please select a file to upload']);
        }

        $pathToFile = $request->file('file');

        if($request->file_type == 'Namibia'){ /* **** Namibia **** */
            $GLOBALS['guid'] = $this->get_guid();
            
            $rows = SimpleExcelReader::create($pathToFile, 'csv')
            ->noHeaderRow()
            ->skip(1)
            ->getRows();
            
            $rows->each(function(array $rowProperties) {
                $count = ++$this->rows;
                if($count < 4){
                    if($count == 1){
                        $date = $rowProperties[0];
                    } else if($count == 2){
                        $account_number = $rowProperties[0];
                    } 
                } else {
                    // dates, as they would be in the document 
                    $__Fake__Demo__Array = [
                        '20220401', '20220402', '20220403', '20220404', '20220405', '20220406', '20220407', '20220408', '20220409', '20220410', '20220411', '20220412', 
                    ];
                    shuffle($__Fake__Demo__Array);


                    DB::table('file_import_namibias')->insert(
                        array(
                            'RecipientAccountHolderName' => $rowProperties[0],
                            'RecipientAccountNumber' => $rowProperties[1],
                            'RecipientAccountType' => $rowProperties[2],
                            'BranchSwiftBicCode' => $rowProperties[3],
                            'RecipientAmount' => $rowProperties[4],
                            'ContractReference' => $rowProperties[5],
                            'Tracking' => $rowProperties[6], 
                            'CollectionReason' => '21', 
                            // ---> set date using $__Fake__Demo__Array ---> //
                            'ActionDate' => $__Fake__Demo__Array[0], 
                            // ---> set date using $__Fake__Demo__Array ---> //
                            'RecipientAccountHolderAbbreviatedName' => 'XXLWNAMI', 
                            'batch_number' => $GLOBALS['guid'], 
                        )
                    );
                }
            });
            return redirect()->route('file-export-namibia-index');

        } elseif($request->file_type == 'Botswana'){ /* **** Botswana **** */
            $dateNow = date('Y-m-d');
            $guid1 = $this->get_guid();
            $guid2 = $this->get_guid();
            $GLOBALS['guid'] = $dateNow.'|'.str_shuffle($guid1.$guid2);
            
            $rows = SimpleExcelReader::create($pathToFile, 'xlsx')
            ->noHeaderRow()
            ->skip(2)
            ->getRows();

            $rows->each(function(array $rowProperties) {
                DB::table('file_import_botswanas')->insert(
                    array(
                        'RecipientAccountHolderName' => $rowProperties[0],
                        'RecipientAccountHolderSurname' => $rowProperties[1],
                        'RecipientAccountHolderInitials' => $rowProperties[2],
                        'RecipientID' => $rowProperties[3],
                        'BranchCode' => $rowProperties[4],
                        'RecipientAccountNumber' => $rowProperties[5],
                        'RecipientNonStandardAccountNumber' => $rowProperties[6], 
                        'RecipientAccountType' => $rowProperties[7], 
                        'AccountReference' => $rowProperties[8], 
                        'RecipientAmount' => $rowProperties[9], 
                        'PolicyNumber' => $rowProperties[10],
                        'ActionDate' => $rowProperties[11], 
                        'Guid' => $GLOBALS['guid'], 
                    )
                );
            });
            return redirect()->route('file-export-botswana-index');
        
        } elseif($request->file_type == 'Capitec'){ /* **** Capitec **** */
            $rows = SimpleExcelReader::create($pathToFile, 'xlsx')
            ->noHeaderRow()
            ->getRows();

            $rows->each(function(array $rowProperties) {
                $RecordIdentifier = substr($rowProperties[0], 0, 2);
                $AccountHolderFullName = substr($rowProperties[0], 124, 30);
                $explode = explode(" ", $AccountHolderFullName);
                $AccountHolderSurame = $explode[0];
                $AccountHolderInitials = trim($explode[1]);

                $DestinationAccountNumber = substr($rowProperties[0], 58, 16); /* User */
                $DestinationBranchCode = substr($rowProperties[0], 52, 6); /* User */
                $PaymentReference = substr($rowProperties[0], 18, 34);
                $Amount = substr($rowProperties[0], 74, 12);
                $ActionDate = substr($rowProperties[0], 86, 8);
                $TransactionUniqueID = substr($rowProperties[0], 94, 30);
                $StatementReference = substr($rowProperties[0], 94, 10);
                $PolicyNumber = $ContractReference = substr($rowProperties[0], 104, 14);
                $CycleDate = substr($rowProperties[0], 118, 6);
                
                $TransactionType = substr($rowProperties[0], 154, 4);
                $ClientType = substr($rowProperties[0], 158, 2);
                $ChargesAccountNumber = substr($rowProperties[0], 160, 16); /* Leza */
                $ServiceType = substr($rowProperties[0], 176, 2);
                $OriginalPaymentReference = substr($rowProperties[0], 178, 34);
                $EntryClass = substr($rowProperties[0], 212, 2);
                $NominatedAccountReference = substr($rowProperties[0], 214, 30);
                $NominatedAccountNumber = substr($rowProperties[0], 2, 16);
                $BDF_Indicator = substr($rowProperties[0], 244, 1);
                
                // capitec branch code 470010
                $BankType = 'Capitec';
                if($DestinationBranchCode != '470010'){$BankType = 'Nedbank';}
                
                if($RecordIdentifier == '02'){
                    // need to check if Policy Number exists
                    // update records if yes, insert if no
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
                        DB::table('mercantile_user_policies')->insert(array('PolicyNumber' => $PolicyNumber,));
    
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
                    
                    // transaction will always insert
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
                            'ServiceType' => $ServiceType,
                            'OriginalPaymentReference' => $OriginalPaymentReference,
                            'EntryClass' => $EntryClass,
                            'NominatedAccountReference' => $NominatedAccountReference,
                            'BDF_Indicator' => $BDF_Indicator,
                            'policy_id' => $PolicyNumber,
                            'Processed' => '0',
                        )
                    );
                } else if($RecordIdentifier == '01'){
                    //header row, saved for Nedbank export
                    DB::table('mercantile_headers')->delete();
                    DB::table('mercantile_headers')->insert(array('HeaderRow' => $rowProperties[0]));
                }
            });

            // V Process Capitec  V
            $export = DB::table('mercantile_user_policies')
                ->join('mercantile_transactions', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_transactions.policy_id')
                ->join('mercantile_users', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_users.policy_id')
                ->join('mercantile_user_banks', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_user_banks.policy_id')
                ->where('mercantile_user_banks.UserBankType', '=', 'Capitec')
                ->orderBy('ActionDate','asc')
                ->get();
            // debit total
            $debitTotal = DB::table('mercantile_transactions')
            ->join('mercantile_user_banks', 'mercantile_transactions.policy_id', '=', 'mercantile_user_banks.policy_id')
            ->where('TransactionType', '=', '0000')
            ->where('mercantile_user_banks.UserBankType', '=', 'Capitec')
            ->sum('Amount');
            // credit total
            $creditTotal = DB::table('mercantile_transactions')
            ->join('mercantile_user_banks', 'mercantile_transactions.policy_id', '=', 'mercantile_user_banks.policy_id')
            ->where('TransactionType', '=', '9999')
            ->where('mercantile_user_banks.UserBankType', '=', 'Capitec')
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
                $Company = 'SCORPION';
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
            // ^ Process Capitec ^

            // V Process Nedbank  V
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
            // ^ Process Nedbank ^
            return redirect()->route('file-export-mercantile-index');
        } 
    }
    public function fileExportIndex() {
        // return an export dashboard
        // baically a nav bar 
        /*
        $namibia_table = FileImportNamibia::latest()->paginate(15);
        return view('FileImport.file-export-index',compact('namibia_table'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
        */
    }

    public function FileDeleteNamibia(){
        //DB::table('file_import_namibias')->delete();
        return view('FileImport.file-import');
    }
    
    public function fileExportNamibiaIndex() { // View Data for Export - Namibia //
        $namibia_table = FileImportNamibia::latest()->paginate(15);
        return view('FileImport.file-export-namibia-index',compact('namibia_table'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function fileExportNamibia(Request $request) {  // Export - Namibia //
        // unable to pass value in Excel function - but can call sql commands
        $actionDate = $request->actionDate;
        DB::table('export_fields')->delete();
        DB::table('export_fields')->insert(
            ['dateField_1' => $actionDate]
        );
        $downloadDocName = 'NamibiaExport_'.date("Y_m_d").'.xlsx';
        return Excel::download(new NamibiaExport, $downloadDocName);
    }

    public function fileExportBotswanaIndex() { // View Data for Export - Botswana //
        $table = FileImportBotswana::latest()->paginate(15);
        return view('FileImport.file-export-botswana-index',compact('table'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function fileExportBotswana(Request $request) {  // Export - Botswana // actionDateFrom  actionDateTo
        // unable to pass value in Excel function - but can call sql commands
        $actionDateFrom = $request->actionDateFrom;
        $actionDateTo = $request->actionDateTo;

        DB::table('export_fields')->delete();
        $values = array('dateField_1' => $actionDateFrom,'dateField_2' => $actionDateTo);
        DB::table('export_fields')->insert($values);

        $downloadDocName = 'BotswanaExport_'.date("Y_m_d").'.xlsx';
        return Excel::download(new BotswanaExport, $downloadDocName);
    }

    public function fileExportBotswanaToText(Request $request){
        // check if file uploaded
        if (!$request->hasFile('file')) {
            return view('FileImport.file-import')->withErrors(['msg' => 'Please select a file to upload']);
        }

        //$myfile = fopen("BotswanaExport_".date("Y_m_d").".txt", "a") or die("Unable to open file!");
        $myfile = fopen("Mercantile_Capitec".date("Y_m_d").".txt", "a") or die("Unable to open file!");
        if ( $xlsx = SimpleXLSX::parse($request->file('file')) ) {
            foreach($xlsx->rows() as $row){
                fwrite($myfile, implode(',',$row) . PHP_EOL);
            }
        } else {
            echo SimpleXLSX::parseError();
        }
        fclose($myfile);

        return view('FileImport.file-import')->withErrors(['msg' => 'File converted to text']);
    }
/*
    public function fileExportBotswanaInstallHeadersIndex() { // View Data for Export - Botswana - InstallHeaders //
        $table = FileImportBotswanaRecordInstallHeader::latest()->paginate(15);
        return view('FileImport.file-export-botswanaInstallHeader-index',compact('table'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    public function fileExportBotswanaInstallHeaders(Request $request) { // Export - InstallHeaders //
        // unable to pass value in Excel function - but can call sql commands
        $actionDate = $request->actionDate;
        DB::table('export_fields')->delete();
        DB::table('export_fields')->insert(
            ['dateField_1' => $actionDate]
        );
        $downloadDocName = 'InstallHeadersExport_'.date("Y-m-d").'.xlsx';
        return Excel::download(new BotswanaRecordInstallHeaderExport, $downloadDocName);
    }
    public function fileExportBotswanaUserHeadersIndex() { // View Data for Export - Botswana - UserHeaders //
        $table = FileImportBotswanaRecordUserHeader::latest()->paginate(15);
        return view('FileImport.file-export-botswanaUserHeader-index',compact('table'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    public function fileExportBotswanaUserHeaders(Request $request) { // Export - UserHeaders //
        // unable to pass value in Excel function - but can call sql commands
        $actionDate = $request->actionDate;
        DB::table('export_fields')->delete();
        DB::table('export_fields')->insert(
            ['dateField_1' => $actionDate]
        );
        $downloadDocName = 'UserHeadersExport_'.date("Y-m-d").'.xlsx';
        return Excel::download(new BotswanaRecordUserHeaderExport, $downloadDocName);
    }
    public function fileExportBotswanaContrasIndex() { // View Data for Export - Botswana - Contra //
        $table = FileImportBotswanaRecordContra::latest()->paginate(15);
        return view('FileImport.file-export-botswanaContras-index',compact('table'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    public function fileExportBotswanaContras(Request $request) { // Export - Contra //
        // unable to pass value in Excel function - but can call sql commands
        $actionDate = $request->actionDate;
        DB::table('export_fields')->delete();
        DB::table('export_fields')->insert(
            ['dateField_1' => $actionDate]
        );
        $downloadDocName = 'ContraExport_'.date("Y-m-d").'.xlsx';
        return Excel::download(new BotswanaRecordContraExport, $downloadDocName);
    }
    public function fileExportBotswanaTransactionsIndex() { // View Data for Export - Botswana - Transactions //
        $table = FileImportBotswanaRecordTransaction::latest()->paginate(15);
        return view('FileImport.file-export-botswanaTransactions-index',compact('table'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    public function fileExportBotswanaTransactions(Request $request) { // Export - Transactions //
        // unable to pass value in Excel function - but can call sql commands
        $actionDate = $request->actionDate;
        DB::table('export_fields')->delete();
        DB::table('export_fields')->insert(
            ['dateField_1' => $actionDate]
        );
        $downloadDocName = 'TransactionsExport_'.date("Y-m-d").'.xlsx';
        return Excel::download(new BotswanaRecordTransactionExport, $downloadDocName);
    }
    public function fileExportBotswanaInstallTrailersIndex() { // View Data for Export - Botswana - InstallTrailers //
        $table = FileImportBotswanaRecordInstallTrailer::latest()->paginate(15);
        return view('FileImport.file-export-botswanaInstallTrailers-index',compact('table'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    public function fileExportBotswanaInstallTrailers(Request $request) { // Export - InstallTrailers //
        // unable to pass value in Excel function - but can call sql commands
        $actionDate = $request->actionDate;
        DB::table('export_fields')->delete();
        DB::table('export_fields')->insert(
            ['dateField_1' => $actionDate]
        );
        $downloadDocName = 'InstallTrailersExport_'.date("Y-m-d").'.xlsx';
        return Excel::download(new BotswanaRecordInstallTrailerExport, $downloadDocName);
    }
    public function fileExportBotswanaUserTrailersIndex() { // View Data for Export - Botswana - UserTrailers //
        $table = FileImportBotswanaRecordUserTrailer::latest()->paginate(15);
        return view('FileImport.file-export-botswanaUserTrailers-index',compact('table'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    public function fileExportBotswanaUserTrailers(Request $request) { // Export - UserTrailers //
        // unable to pass value in Excel function - but can call sql commands
        $actionDate = $request->actionDate;
        DB::table('export_fields')->delete();
        DB::table('export_fields')->insert(
            ['dateField_1' => $actionDate]
        );
        $downloadDocName = 'UserTrailersExport_'.date("Y-m-d").'.xlsx';
        return Excel::download(new BotswanaRecordUserTrailerExport, $downloadDocName);
    }
*/
}




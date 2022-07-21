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




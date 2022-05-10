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
use App\Exports\NamibiaExport;
use App\Exports\BotswanaExport;
use App\Exports\BotswanaRecordUserTrailerExport;
use App\Exports\BotswanaRecordUserHeaderExport;
use App\Exports\BotswanaRecordTransactionExport;
use App\Exports\BotswanaRecordInstallTrailerExport;
use App\Exports\BotswanaRecordInstallHeaderExport;
use App\Exports\BotswanaRecordContraExport;
use App\Traits\Guid;

/*
021001        40550021yymmddyymmdd000118000180MAGTAPE                                                                                                                              
044055yymmddyymmddyymmddyymmdd0000010001SAMEDAY                                                                                                                                     
1025064502000076260405500000904262600051292025100000043898yymmdd610000USERABNAMEUSER REFERENCE 6    HOMING ACCOUNT                       00000000000000000000           21            
1225064502000076260405500001025064502000076260100000043898yymmdd100000USERABNAMECONTRA 1            NOMINATED ACCOUNT NAME 1                                                       
1025064502000076260405500001144011001016339501100000026620yymmdd610000USERABNAMEUSER REFERENCE 7  HOMING ACCOUNT               00000000000000000000           21           
1025064502000076260405500001317133801713217600100000031045yymmdd610000USERABNAMEUSER REFERENCE 8  HOMING ACCOUNT               00000000000000000000           21           
1025064502000076260405500001502090900070827915100000015418yymmdd610000USERABNAMEUSER REFERENCE 9  HOMING ACCOUNT               00000000000000000000           21           
1225064502000076260405500001625064502000076260100000015418yymmdd100000USERABNAMECONTRA 2            NOMINATED ACCOUNT NAME 2                                                       
1025064502000076260405500001763200500712146508100000118156yymmdd610000USERABNAMEUSER REFERENCE 10   HOMING ACCOUNT                00000000000000000000           21           
1025064502000076260405500001933431502740530111100000051258yymmdd610000USERABNAMEUSER REFERENCE 11   HOMING ACCOUNT                00000000000000000000           21           
1225064502000076260405500002025064502000076260100000051258yymmdd100000USERABNAMECONTRA 3            NOMINATED ACCOUNT NAME 1                                                       
1025064502000076260405500002100400500001798529100000096633yymmdd610000USERABNAMEUSER REFERENCE 12   HOMING ACCOUNT                00000000000000000000           21           
1025064502000076260405500002213042601304071170100000680305yymmdd610000USERABNAMEUSER REFERENCE 13   HOMING ACCOUNT               00000000000000000000           21   

INSTALLATION HEADER RECORD
021001        40550021yymmddyymmdd000118000180MAGTAPE

USER HEADER RECORD
044055yymmddyymmddyymmddyymmdd0000010001SAMEDAY

STANDARD TRANSACTION RECORD
1025064502000076260405500000163200500710423598100000372868yymmdd210000USERABNAMEUSER REFERENCE 1    HOMING ACCOUNT                00000000000000000000               21

CONTRA RECORD
1225064502000076260405500000425064502000076260100000476387yymmdd100000USERABNAMECONTRA 1            NOMINATED ACCOUNT NAME 1

USER TRAILER RECORD
924055000001000008yymmddyymmdd000005000003000003000000543796000000543796047861444007

INSTALLATION TRAILER RECORD
941001        40550021yymmddyymmdd000118000180MAGTAPE   000003000029000004
*/
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
        
        if($request->file_type == 'Namibia'){
            /*
            $dateNow = date('Y-m-d');
            $guid1 = $this->get_guid();
            $guid2 = $this->get_guid();
            $GLOBALS['guid'] = $dateNow.'|'.str_shuffle($guid1.$guid2);
            */
            $GLOBALS['guid'] = $this->get_guid();
            
            $rows = SimpleExcelReader::create($pathToFile, 'csv')
            ->noHeaderRow()
            ->skip(1)
            ->getRows();
            dd('qq');
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
        } elseif($request->file_type == 'Botswana'){
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
            /*
            SQLSTATE[42S02]: Base table or view not found: 1146 Table 'laravel.file_import_botswanas' doesn't exist 
            (SQL: insert into `file_import_botswanas` (`RecipientAccountHolderName`) 
            values (Shawn 3;Whelan 3;SPW;8202275195083;100003;12345678903;;1;AccountReference 3;1053.59;P0008883;20220403;;;))
            */
        } elseif($request->file_type == 'BotswanaInstallHeaderRecord'){
            //INSTALLATION HEADER RECORD // 021001        40550021yymmddyymmdd000118000180MAGTAPE
            $rows = SimpleExcelReader::create($pathToFile, 'csv')
            ->noHeaderRow()
            ->skip(1)
            ->getRows();

            $rows->each(function(array $rowProperties) {
                $str = $rowProperties[0];
                dd($str);

                $RecordIdentifier = substr($str, 0, 2); 
                $VolumeNumber = substr($str, 2, 4); 
                // as $TapeSerialNumber can be 8 characters white spaces, or any 8 characters, the best way is to split by the value.
                // so get the characters of $TapeSerialNumber, and split by the value, and continue with $str2
                $TapeSerialNumber = substr($str, 6, 8);

                // need to figure this out, the blank or anything value is messing this up
                /*
                $array1 = explode($TapeSerialNumber,$str);
                $str2 = $array1[2];
                dd($str2);
                */

                $str2 = '40550021221215220825000118000180MAGTAPE;;;;;';
                
                $InstallationIDfrom = substr($str2, 0, 4); 
                $InstallationIDto = substr($str2, 4, 4); 
                $creationYear = substr($str2, 8, 2); 
                $creationMonth = substr($str2, 10, 2); 
                $creationDay = substr($str2, 12, 2); 
                $purgeYear = substr($str2, 14, 2); 
                $purgeMonth = substr($str2, 16, 2); 
                $purgeDay = substr($str2, 18, 2); 
                $generationNumber = substr($str2, 20, 4); 
                $blockLength = substr($str2, 24, 4); 
                $recordLength = substr($str2, 28, 4); 
                $service = substr($str2, 32, 10); 

                $creationDate = '20'.$creationYear.$creationMonth.$creationDay;
                $purgeDate = '20'.$purgeYear.$purgeMonth.$purgeDay;

                DB::table('file_import_botswana_record_install_headers')->insert(
                    array(
                        'RecordIdentifier' => $RecordIdentifier,
                        'VolumeNumber' => $VolumeNumber,
                        'TapeSerialNumber' => $TapeSerialNumber,
                        'InstallationIDfrom' => $InstallationIDfrom,
                        'InstallationIDto' => $InstallationIDto,
                        'CreationDate' => $creationDate,
                        'PurgeDate' => $purgeDate,
                        'GenerationNumber' => $generationNumber,
                        'BlockLength' => $blockLength,
                        'RecordLength' => $recordLength,
                        'Service' => $service,
                    )
                );
            }); 
            return redirect()->route('file-export-botswana-install-headers-index');
        } elseif($request->file_type == 'BotswanaUserHeaderRecord'){
            // USER HEADER RECORD // 044055yymmddyymmddyymmddyymmdd0000010001SAMEDAY
            $rows = SimpleExcelReader::create($pathToFile, 'csv')
            ->noHeaderRow()
            ->skip(1)
            ->getRows();

            $rows->each(function(array $rowProperties) {
                $str = $rowProperties[0];

                $RecordIdentifier = substr($str, 0, 2); 
                $UserCode = substr($str, 2, 4); 
                $CreationDate = '20'.substr($str, 6, 6); 
                $PurgeDate = '20'.substr($str, 12, 6); 
                $ActionDateFirst = '20'.substr($str, 18, 6); 
                $ActionDateLast = '20'.substr($str, 24, 6); 
                $SequenceNumber = substr($str, 30, 6); 
                $GenerationNumber = substr($str, 36, 4); 
                $Service = substr($str, 40, 10); 

                DB::table('file_import_botswana_record_user_headers')->insert(
                    array(
                        'RecordIdentifier' => $RecordIdentifier,
                        'UserCode' => $UserCode,
                        'CreationDate' => $CreationDate,
                        'PurgeDate' => $PurgeDate,
                        'ActionDateFirst' => $ActionDateFirst,
                        'ActionDateLast' => $ActionDateLast,
                        'SequenceNumber' => $SequenceNumber,
                        'GenerationNumber' => $GenerationNumber,
                        'Service' => $Service,
                    )
                );
            }); 
            return redirect()->route('file-export-botswana-user-headers-index');
        } elseif($request->file_type == 'BotswanaTransactions'){
            // STANDARD TRANSACTION RECORD // 1025064502000076260405500000163200500710423598100000372868yymmdd210000USERABNAMEUSER REFERENCE 1    HOMING ACCOUNT                00000000000000000000               21
            $rows = SimpleExcelReader::create($pathToFile, 'csv')
            ->noHeaderRow()
            ->skip(1)
            ->getRows();

            $rows->each(function(array $rowProperties) {
                $str = $rowProperties[0];

                $RecordIdentifier = substr($str, 0, 2); 
                $UserBranch = substr($str, 2, 6); 
                //dd($UserBranch);
                $UserAccountNumber = substr($str, 8, 11); 
                $UserCode = substr($str, 19, 4); 
                $SequenceNumber = substr($str, 23, 6); 
                $HomingBranch = substr($str, 29, 6); 
                $HomingAccountNumber = substr($str, 40, 11); 
                $AccountType = substr($str, 41, 1); 
                $Amount = substr($str, 52, 11); 
                $ActionDate = '20'.substr($str, 58, 6); 
                $EntryType = substr($str, 60, 2); 
                $TaxCode = substr($str, 61, 1); 
                // filler 3 - 000
                $UserAbbreviatedName = substr($str, 65, 10); 
                $UserReference = substr($str, 75, 10); 
                // filler 10 - |          |
                $HomingAccountName = substr($str, 95, 15); 
                // filler 15 - |               |
                $NonStandardAccountNumber = substr($str, 125, 20); 
                // filler 16 - |                |
                $HomingInstitution = substr($str, 161, 2); 

                DB::table('file_import_botswana_record_transactions')->insert(
                    array(
                        'RecordIdentifier' => $RecordIdentifier,
                        'UserBranch' => $UserBranch,
                        'UserAccountNumber' => $UserAccountNumber,
                        'UserCode' => $UserCode,
                        'SequenceNumber' => $SequenceNumber,
                        'HomingBranch' => $HomingBranch,
                        'HomingAccountNumber' => $HomingAccountNumber,
                        'AccountType' => $AccountType,
                        'Amount' => $Amount,
                        'ActionDate' => $ActionDate,
                        'EntryType' => $EntryType,
                        'TaxCode' => $TaxCode,
                        'UserAbbreviatedName' => $UserAbbreviatedName,
                        'UserReference' => $UserReference,
                        'HomingAccountName' => $HomingAccountName,
                        'NonStandardAccountNumber' => $NonStandardAccountNumber,
                        'HomingInstitution' => $HomingInstitution,
                    )
                );
            }); 
            return redirect()->route('file-export-botswana-transactions-index');
        } elseif($request->file_type == 'BotswanaContras'){
            // CONTRA RECORD // 1225064502000076260405500000425064502000076260100000476387yymmdd100000USERABNAMECONTRA 1            NOMINATED ACCOUNT NAME 1
            /*
            12
            250645
            0200
            0076260405500000425064502000076260100000476387yymmdd100000USERABNAMECONTRA 1            NOMINATED ACCOUNT NAME 1
            */
            $rows = SimpleExcelReader::create($pathToFile, 'csv')
            ->noHeaderRow()
            ->skip(1)
            ->getRows();

            $rows->each(function(array $rowProperties) {
                $str = $rowProperties[0];

                $RecordIdentifier = substr($str, 0, 2); 
                $UserBranch = substr($str, 2, 6); 
                $HomingAccountNumber = substr($str, 8, 11); 
                $UserCode = substr($str, 19, 4); 
                $SequenceNumber = substr($str, 23, 6); 
                $HomingBranch = substr($str, 29, 6); 
                $HomingAccountNumber = substr($str, 35, 11); 
                $AccountType = substr($str, 46, 1); 
                $Amount = substr($str, 47, 11); 
                $ActionDate = '20'.substr($str, 58, 6); 
                $EntryType = substr($str, 64, 2); 
                // filler 4 - 0000
                $UserAbbreviatedName = substr($str, 70, 10); 
                $UserReference = substr($str, 80, 20); 
                //$AccountName = substr($str, 87, 30); 

                DB::table('file_import_botswana_record_contras')->insert(
                    array(
                        'RecordIdentifier' => $RecordIdentifier,
                        'UserBranch' => $UserBranch,
                        'UserCode' => $UserCode,
                        'SequenceNumber' => $SequenceNumber,
                        'HomingBranch' => $HomingBranch,
                        'HomingAccountNumber' => $HomingAccountNumber,
                        'AccountType' => $AccountType,
                        'Amount' => $Amount,
                        'ActionDate' => $ActionDate,
                        'EntryType' => $EntryType,
                        'UserAbbreviatedName' => $UserAbbreviatedName,
                        'UserReference' => $UserReference,
                        //'AccountName' => $AccountName,
                    )
                );
            }); 
            return redirect()->route('file-export-botswana-contras-index');
        } elseif($request->file_type == 'BotswanaUserTrailers'){
            // USER TRAILER RECORD // 924055000001000008yymmddyymmdd000005000003000003000000543796000000543796047861444007
            $rows = SimpleExcelReader::create($pathToFile, 'csv')
            ->noHeaderRow()
            ->skip(1)
            ->getRows();

            $rows->each(function(array $rowProperties) {
                $str = $rowProperties[0];

                $RecordIdentifier = substr($str, 0, 2); 
                $UserCode = substr($str, 2, 4); 
                $SequenceNumberFirst = substr($str, 6, 6); 
                $SequenceNumberLast = substr($str, 12, 6); 
                $ActionDateFirst = '20'.substr($str, 18, 6); 
                $ActionDateLast = '20'.substr($str, 24, 6); 
                $NumberDebitRecords = substr($str, 30, 6); 
                $NumberCreditRecords = substr($str, 36, 6); 
                $NumberContraRecords = substr($str, 42, 6); 
                $TotalDebitValue = substr($str, 54, 12); 
                $TotalCreditValue = substr($str, 66, 12); 
                $HashTotalofHomingAccountNumbers = substr($str, 78, 12); 
                //dd($TotalCreditValue);

                DB::table('file_import_botswana_record_user_trailers')->insert(
                    array(
                        'RecordIdentifier' => $RecordIdentifier,
                        'UserCode' => $UserCode,
                        'SequenceNumberFirst' => $SequenceNumberFirst,
                        'SequenceNumberLast' => $SequenceNumberLast,
                        'ActionDateFirst' => $ActionDateFirst,
                        'ActionDateLast' => $ActionDateLast,
                        'NumberDebitRecords' => $NumberDebitRecords,
                        'NumberCreditRecords' => $NumberCreditRecords,
                        'NumberContraRecords' => $NumberContraRecords,
                        'TotalDebitValue' => $TotalDebitValue,
                        'TotalCreditValue' => $TotalCreditValue,
                        'HashTotalofHomingAccountNumbers' => $HashTotalofHomingAccountNumbers,
                    )
                );
            }); 
            return redirect()->route('file-export-botswana-user-trailers-index');
        } elseif($request->file_type == 'BotswanaInstallTrailers'){
            // INSTALLATION TRAILER RECORD // 941001        40550021yymmddyymmdd000118000180MAGTAPE   000003000029000004
            $rows = SimpleExcelReader::create($pathToFile, 'csv')
            ->noHeaderRow()
            ->skip(1)
            ->getRows();

            $rows->each(function(array $rowProperties) {
                $str = $rowProperties[0];

                $RecordIdentifier = substr($str, 0, 2); 
                $VolumeNumber = substr($str, 2, 4); 
                // as $TapeSerialNumber can be 8 characters white spaces, or any 8 characters, the best way is to split by the value.
                // so get the characters of $TapeSerialNumber, and split by the value, and continue with $str2
                $TapeSerialNumber = substr($str, 6, 8);
                
                //$array1 = explode($TapeSerialNumber,$str);
                //$str2 = $array1[2];

                $str2 = '40550021221215220825000118000180MAGTAPE000003000029000004';
                //40550021221215220825000118000180MAGTAPE000003000029000004


                $InstallationIDfrom = substr($str2, 0, 4); 
                $InstallationIDto = substr($str2, 4, 4); 
                $CreationDate = '20'.substr($str2, 8, 6); 
                $PurgeDate = '20'.substr($str2, 14, 6); 
                $GenerationNumber = substr($str2, 20, 4); 
                $BlockLength = substr($str2, 24, 4); 
                $RecordLength = substr($str2, 28, 4); 
                $Service = substr($str2, 38, 10); 
                $BlockCount = substr($str2, 44, 6); 
                $RecordCount = substr($str2, 50, 6); 
                $UserHeaderTrailerCount = substr($str2, 56, 6); 

                DB::table('file_import_botswana_record_install_trailers')->insert(
                    array(
                        'RecordIdentifier' => $RecordIdentifier,
                        'VolumeNumber' => $VolumeNumber,
                        'TapeSerialNumber' => $TapeSerialNumber,
                        'InstallationIDfrom' => $InstallationIDfrom,
                        'InstallationIDto' => $InstallationIDto,
                        'CreationDate' => $CreationDate,
                        'PurgeDate' => $PurgeDate,
                        'GenerationNumber' => $GenerationNumber,
                        'BlockLength' => $BlockLength,
                        'RecordLength' => $RecordLength,
                        'Service' => $Service,
                        'BlockCount' => $BlockCount,
                        'RecordCount' => $RecordCount,
                        'UserHeaderTrailerCount' => $UserHeaderTrailerCount,
                    )
                );
            }); 
            return redirect()->route('file-export-botswana-install-trailers-index');
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

    // +=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+= //





    // +=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+= //

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

    // +=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+= //

    public function fileExportBotswanaToText(Request $request){
        // check if file uploaded
        if (!$request->hasFile('file')) {
            return view('FileImport.file-import')->withErrors(['msg' => 'Please select a file to upload']);
        }
        //dd('qq');




    }
    // +=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+= //

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
}




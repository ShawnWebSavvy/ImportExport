<?php 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
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
use App\Models\capitec_accounts1000s;
use App\Models\MercantileTransactionRejections;
use App\Models\MercantileNominatedBank;
use App\Models\MercantileHeader;
use App\Exports\NamibiaExport;
use App\Exports\BotswanaExport;
use App\Exports\MercantileCapitecRejectionsExport;
use App\Exports\BotswanaRecordUserTrailerExport;
use App\Exports\BotswanaRecordUserHeaderExport;
use App\Exports\BotswanaRecordTransactionExport;
use App\Exports\BotswanaRecordInstallTrailerExport;
use App\Exports\BotswanaRecordInstallHeaderExport;
use App\Exports\BotswanaRecordContraExport;
use App\Traits\Guid;
use Illuminate\Support\Facades\Storage;
use File;

use Illuminate\Support\LazyCollection;
use App\Models\Deduction;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Shuchkin\SimpleXLSX;

use App\Jobs\ProcessUpload;
use App\Jobs\ProcessCapitec;
use App\Jobs\ProcessNedbank;
use App\Jobs\CapitecTest_1000;
use App\Jobs\CapitecRejections;
use App\Jobs\SendEmail;
use App\Jobs\ArchiveDeleteCapitec;
use App\Jobs\ArchiveDeleteNedbank;
use App\Jobs\processInputToOutput;
use App\Jobs\processTransactions;
use App\Jobs\deleteTransactions;

use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationEmail;

use App\Console\Commands\TestCommand;
//require('App\Console\Commands\TestCommand.php');
//require('App/Console/Commands/TestCommand.php');

///var/www/html/rocket-switch/app/Console/Commands

use App\Models\transaction;

use App\Http\Controllers\MailController;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Artisan;



use App\Exports\TransactionExport;

class FileController extends Controller{
    public function fileImportIndex(){
       return view('FileImport.file-import');
    }

    public function fileUpload_Simple(Request $request) {
        session_start();
        if (!$request->hasFile('file')) {
            return view('FileImport.file-import')->withErrors(['msg' => 'Please select a file to upload']);}
        $checkTest = DB::table('deductions')->first();
            if($checkTest){
                return view('FileImport.file-import')->withErrors(['msg' => 'Deductions currently being processed, please wait till completed']);}
        
        $_SESSION['pathToFile'] = $request->file('file');

        if($request->file_type == 'Capitec'){
        
            LazyCollection::make(function () {
                $handle = fopen($_SESSION['pathToFile'],"r");
                while (($line = fgetcsv($handle, 4096)) !== false) {
                    yield $line;
                }
                fclose($handle);
            })
            ->chunk(1000)
            ->each(function (LazyCollection $chunk) {
                $MercantileUserPolicy = $chunk->map(function ($line) {
                    $PolicyNumber = substr($line[0], 104, 14);
                    return [
                        'PolicyNumber' => $PolicyNumber,
                        'row' => $line[0]
                    ];
                })->toArray();
                Deduction::addNew($MercantileUserPolicy); 
            });

            //Artisan::call('command:');
            Artisan::queue('command:processTransactions');

            return redirect()->route('file-import')->withErrors(['msg' => 'Transactions are currently being processed. An email will be sent to you when the files are ready for download.']);

        } else if ($request->file_type == 'CapitecTestData'){
            // check if capitec test data loaded
            $checkTest = DB::table('capitec_accounts1000s')
            ->where('capitec_accounts1000s.dummy_data_Capitec_active', '=', '1')
            ->first();
            if($checkTest){
                return view('FileImport.file-import')->withErrors(['msg' => 'Test Data already populated']);
            }

            $file = fopen($_SESSION['pathToFile'],"r");
            while(! feof($file)){
                $rowProperties = fgets($file);

                $PolicyNumber = substr($rowProperties, 104, 14);

                $AccountHolderFullName = substr($rowProperties, 124, 30);
                $explode = explode(" ", $AccountHolderFullName);
                $AccountHolderSurame = $explode[0];
                $AccountHolderInitials = trim($explode[1]);
                $ClientType = substr($rowProperties, 158, 2);

                $DestinationAccountNumber = substr($rowProperties, 58, 16);
                $DestinationBranchCode = substr($rowProperties, 52, 6);

                $BankType = 'Capitec';
                    if($DestinationBranchCode != '470010'){$BankType = 'Nedbank';}

                DB::table('mercantile_user_policies')->insert(
                    array(
                        'PolicyNumber' => $PolicyNumber,
                        'dummy_data_Capitec_active' => 1,
                    )
                );

                DB::table('capitec_accounts1000s')->insert(
                    array(
                        'PolicyNumber' => $PolicyNumber,
                        'dummy_data_Capitec_active' => 1,
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
            return redirect()->route('file-import')->withErrors(['msg' => 'Capitec 1000 Accounts processed successfully']);

        } else if ($request->file_type == 'CapitecRejections'){
            LazyCollection::make(function () {
                $handle = fopen($_SESSION['pathToFile'],"r");
                while (($line = fgetcsv($handle, 4096)) !== false) {
                    yield $line;
                }
                fclose($handle);
            })
            ->chunk(1000)
            ->each(function (LazyCollection $chunk) {
                $MercantileUserPolicy = $chunk->map(function ($line) {
                    $PolicyNumber = substr($line[0], 104, 14);
                    return [
                        'PolicyNumber' => $PolicyNumber,
                        'row' => $line[0]
                    ];
                })->toArray();
                Deduction::addNew($MercantileUserPolicy); 
            });
            //Artisan::call('command:processRejections');
            Artisan::queue('command:processRejections');
            return redirect()->route('file-import')->withErrors(['msg' => 'Rejections are currently being processed. An email will be sent to you when the file is ready for download.']);
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
}




<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\RejectionsCompleteEmail;
use Illuminate\Support\Facades\DB;
use App\Models\Deduction;
use File;
use Illuminate\Support\Facades\Storage;

class processRejections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:processRejections';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'return text file of capitec transaction rejections';

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
        DB::table('mercantile_capitec_rejections')
            ->where('Processed', 0)
            ->update(['Processed' => 1,]);
        
        //$file = fopen($_SESSION['pathToFile'],"r");
        //while(! feof($file)){
        $deductionsArray = Deduction::all();
        foreach ($deductionsArray as $rowProperties) {
            //$rowProperties = fgets($file);
            $rowProperties = $rowProperties->row;
            $RecordIdentifier = substr($rowProperties, 0, 2);

            if($RecordIdentifier == '02'){
                $DestinationBranchCode = substr($rowProperties, 52, 6); 
                $Amount = substr($rowProperties, 74, 12);
                $PolicyNumber = $ContractReference = substr($rowProperties, 104, 14);
                
                // capitec branch code 470010
                $BankType = 'Capitec';
                if($DestinationBranchCode != '470010'){$BankType = 'Nedbank';}

                if($BankType == 'Capitec'){
                    //$check = DB::table('mercantile_user_policies')
                    $check = DB::table('capitec_accounts1000s')
                    ->where('PolicyNumber', '=', $PolicyNumber)
                    ->where('dummy_data_Capitec_active', '=', '1')
                    ->first();

                    if ($check) { 
                        $transacion = DB::table('mercantile_capitec_transactions_archives')
                            ->where('policy_id', '=', $PolicyNumber)
                            ->where('Processed', '=', '0')
                            ->first();
                        if(isset($transacion->id)){
                            DB::table('mercantile_capitec_rejections')->insert(
                                array(
                                    'policy_id' => $PolicyNumber,
                                    'Processed' => '0',
                                    'transaction_id' => $transacion->id,
                                    'Amount' => $Amount,
                                )
                            );
                        }
                    }
                }
            }else if($RecordIdentifier == '01'){
                DB::table('mercantile_headers')->delete();
                DB::table('mercantile_headers')->insert(array('HeaderRow' => $rowProperties));
            }
        }

        $files = Storage::files("downloads/mercantile/rejectionsCurrent/");
        foreach ($files as $value) {
            $file_name = str_replace('downloads/mercantile/rejectionsCurrent/','', $value);
            File::move(storage_path('app/downloads/mercantile/rejectionsCurrent/'.$file_name), storage_path('app/downloads/mercantile/rejectionsArchive/'.$file_name));
        }
        
        $pathCapitecRejections = 'CapitecRejections_'.date("Y_m_d").'.txt';
        $file = fopen($pathCapitecRejections,"w");
        $header = DB::table('mercantile_headers')->first();
        $header = $header->HeaderRow;
        fwrite($file, $header."\r\n");

        $export = DB::table('mercantile_capitec_rejections')
        ->join('mercantile_user_banks', 'mercantile_capitec_rejections.policy_id', '=', 'mercantile_user_banks.policy_id')
        ->join('mercantile_users', 'mercantile_capitec_rejections.policy_id', '=', 'mercantile_users.policy_id')
        ->join('mercantile_capitec_transactions_archives', 'mercantile_capitec_rejections.policy_id', '=', 'mercantile_capitec_transactions_archives.policy_id')
        ->where('mercantile_capitec_rejections.Processed', '=', '0')
        ->where('mercantile_capitec_transactions_archives.Processed', '=', '0')
        ->orderBy('TransactionOrder','asc')
        ->get();
        $total_return = count($export) - 1;

        $rejectionsTotal = DB::table('mercantile_capitec_rejections')
            ->sum('Amount');
        
        $rejectionsTotal = str_replace(".0","",$rejectionsTotal);
        $str_length = strlen($rejectionsTotal);
        $zero = '000000000000000000';
        $rejectionsTotal = $zero . $rejectionsTotal;
        $rejectionsTotal = substr($rejectionsTotal, $str_length, 18);

        foreach($export as $key => $v){
            $RecordIdentifier = $v->RecordIdentifier;
            $PaymentReference = $v->PaymentReference;
            $UserBranchCode = $v->UserBranchCode;
            $UserAccountNumber = $v->UserAccountNumber;
            $Amount = $v->Amount;
            $ActionDate = $v->ActionDate;
            $TransactionUniqueID = $v->TransactionUniqueID;
            $AccountHolderFullName = $v->AccountHolderFullName;
            $TransactionType = $v->TransactionType;
            $ClientType = $v->ClientType;
            $ServiceType = $v->ServiceType;
            $OriginalPaymentReference = $v->OriginalPaymentReference;
            $EntryClass = $v->EntryClass;
            $NominatedAccountReference = $v->NominatedAccountReference;
            $BDF_Indicator = $v->BDF_Indicator;
        
            $ActionDate = explode("-", $ActionDate);
            $ActionDate = implode("", $ActionDate);

            $rejectionRecord = 
            $RecordIdentifier.'0000001454088281'.$PaymentReference.$UserBranchCode.$UserAccountNumber.$Amount.
            $ActionDate.$TransactionUniqueID.$AccountHolderFullName.$TransactionType.$ClientType.
            '0000001454088281'.$ServiceType.$OriginalPaymentReference.$EntryClass.$NominatedAccountReference.$BDF_Indicator.
            '                                                                           ';

            fwrite($file, $rejectionRecord."\r\n");
            //Storage::disk('local')->append('CapitecRejections_'.date("Y_m_d").'.txt', $rejectionRecord);
            //Storage::put('downloads/mercantile/rejectionsCurrent/CapitecRejections_'.date("Y_m_d").'.txt', $rejectionRecord); 

            if($key == $total_return){
                $str_length = strlen($total_return);
                $zero = '00000000';
                $total_return = $zero . $total_return;
                $total_return = substr($total_return, $str_length, 8);

                $trailer_record = 
                    '03'.$total_return.$rejectionsTotal.
                    '                                                                                                                                                                                                                                                                                                    ';
                fwrite($file, $trailer_record."\r\n");
                fclose($file);
                File::move($pathCapitecRejections, storage_path('app/downloads/mercantile/rejectionsCurrent/'.$pathCapitecRejections));
                //File::move($pathCapitecRejections, storage_path('app/downloads/mercantile/current/'.$pathCapitecRejections));
                //Storage::disk('local')->append('CapitecRejections_'.date("Y_m_d").'.txt', $trailer_record);
                //Storage::put('downloads/mercantile/rejectionsCurrent/CapitecRejections_'.date("Y_m_d").'.txt', $trailer_record);
            }
        }
    // delete deductions table, for a new clean import
    DB::table('deductions')->delete();
    Mail::to('tracyv@leza.co.za')->send(new TransactionsCompleteEmail());
    Mail::to('shawnw@leza.co.za')->send(new RejectionsCompleteEmail());
    // ^ Process Rejections ^
    }
}

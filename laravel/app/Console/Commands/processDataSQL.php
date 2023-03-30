<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class processDataSQL extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:processDataSQL';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        DB::table('deductions')->delete();
    }
}

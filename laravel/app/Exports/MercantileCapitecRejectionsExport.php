<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class MercantileCapitecRejectionsExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        $header = DB::table('mercantile_headers')->first();
        $header = [$header->HeaderRow];
        return [$header];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //dd('qq');
        /*
        db calls

        mercantile_capitec_rejections
        policy_id
        transaction_id
        Amount
        Processed

        mercantile_capitec_transactions_archives
        RecordIdentifier
        PaymentReference
        Amount
        ActionDate
        TransactionUniqueID
        StatementReference
        CycleDate
        TransactionType
        TransactionOrder
        ServiceType
        OriginalPaymentReference
        EntryClass
        NominatedAccountReference
        BDF_Indicator
        policy_id

        mercantile_user_banks
        UserAccountNumber
        UserBranchCode
        UserBankType

        mercantile_users
        AccountHolderFullName
        ClientType
        */

        $export = DB::table('mercantile_capitec_rejections')
            ->join('mercantile_user_banks', 'mercantile_capitec_rejections.policy_id', '=', 'mercantile_user_banks.policy_id')
            ->join('mercantile_users', 'mercantile_capitec_rejections.policy_id', '=', 'mercantile_users.policy_id')
            ->join('mercantile_capitec_transactions_archives', 'mercantile_capitec_rejections.policy_id', '=', 'mercantile_capitec_transactions_archives.policy_id')
            ->where('mercantile_capitec_rejections.Processed', '=', '0')
            ->orderBy('TransactionOrder','asc')
            ->get();
        $GLOBALS['total_return'] = count($export) - 1;
        
        $rejectionsTotal = DB::table('mercantile_capitec_rejections')
            ->sum('Amount');
        
        $rejectionsTotal = str_replace(".0","",$rejectionsTotal);
        $str_length = strlen($rejectionsTotal);
        $zero = '000000000000000000';
        $rejectionsTotal = $zero . $rejectionsTotal;
        $GLOBALS['rejectionsTotal'] = substr($rejectionsTotal, $str_length, 18);

        /*
        foreach($export as $v){
            dd($v);
        }
        */
        //$NominatedAccountNumber = '0000001454088281';


        $array = $export->map(function ($export, $key) {
            $RecordIdentifier = $export->RecordIdentifier;
            $PaymentReference = $export->PaymentReference;
            $UserBranchCode = $export->UserBranchCode;
            $UserAccountNumber = $export->UserAccountNumber;
            $Amount = $export->Amount;
            $ActionDate = $export->ActionDate;
            $TransactionUniqueID = $export->TransactionUniqueID;
            $AccountHolderFullName = $export->AccountHolderFullName;
            $TransactionType = $export->TransactionType;
            $ClientType = $export->ClientType;
            $ServiceType = $export->ServiceType;
            $OriginalPaymentReference = $export->OriginalPaymentReference;
            $EntryClass = $export->EntryClass;
            $NominatedAccountReference = $export->NominatedAccountReference;
            $BDF_Indicator = $export->BDF_Indicator;
        
            
            $ActionDate = explode("-", $ActionDate);
            $ActionDate = implode("", $ActionDate);
            //$ActionDate = substr($ActionDate, 2); 
            

            $rejectionRecord = [
            $RecordIdentifier.'0000001454088281'.$PaymentReference.$UserBranchCode.$UserAccountNumber.$Amount.
            $ActionDate.$TransactionUniqueID.$AccountHolderFullName.$TransactionType.$ClientType.
            '0000001454088281'.$ServiceType.$OriginalPaymentReference.$EntryClass.$NominatedAccountReference.$BDF_Indicator.
            '                                                                           '];

            if($key == $GLOBALS['total_return']){
                // build trailer record return
                // 03
                // 00006587
                // 000000000067073664 

                $str_length = strlen($GLOBALS['total_return']);
                $zero = '00000000';
                $total_return = $zero . $GLOBALS['total_return'];
                $total_return = substr($total_return, $str_length, 8);


                

                $trailer_record = [
                    '03'.$total_return.$GLOBALS['rejectionsTotal'].
                    '                                                                                                                                                                                                                                                                                                    '
                ];
                return [$rejectionRecord, $trailer_record];
            }
            // standard rejection record return 
            return [$rejectionRecord];
        });


        
        return $array;


        
        
        //return mercantile_capitec_rejections::all();
    }
}
 
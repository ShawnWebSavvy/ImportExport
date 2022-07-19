<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MercantileUser;
use App\Models\MercantileTransaction;
use App\Models\MercantileUserBank;
use App\Models\MercantileUserPolicy;
use App\Models\MercantileTransactionRejections;
use App\Models\MercantileNominatedBank;
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
    public function fileExportMercantileIndex(){
        /*
        $namibia_table = FileImportNamibia::latest()->paginate(15);
        return view('FileImport.file-export-namibia-index',compact('namibia_table'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
        */

        /*
        <th>Policy</th>                     -->     mercantie_(all)
        <th>Action Date</th>                -->     mercantile_transaction
        <th>User Full Name</th>             -->     mercantile_users

        NB - Bank - Nedbank - Capitec       -->     mercantile_banks
        */

        /*
        $nedbankQuery = DB::table('mercantile_user_policies')
        ->join('mercantile_transactions', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_transactions.policy_id')
        ->join('mercantile_users', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_users.policy_id')
        ->join('mercantile_user_banks', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_user_banks.policy_id')
        ->where('mercantile_user_banks.UserBankType', '=', 'Nedbank')
        ->where('mercantile_transactions.Processed', '=', '0')
        ->paginate(15);

        $capitecQuery = DB::table('mercantile_user_policies')
        ->join('mercantile_transactions', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_transactions.policy_id')
        ->join('mercantile_users', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_users.policy_id')
        ->join('mercantile_user_banks', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_user_banks.policy_id')
        ->where('mercantile_user_banks.UserBankType', '=', 'Capitec')
        ->where('mercantile_transactions.Processed', '=', '0')
        ->paginate(15);
        */

        /*
        return view('Mercantile.file-export-mercantile-index',
        compact('nedbankQuery'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
        */
        
        return view('Mercantile.file-export-mercantile-index', [
            'nedbankQuery' => DB::table('mercantile_user_policies')
            ->join('mercantile_transactions', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_transactions.policy_id')
            ->join('mercantile_users', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_users.policy_id')
            ->join('mercantile_user_banks', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_user_banks.policy_id')
            ->where('mercantile_user_banks.UserBankType', '=', 'Nedbank')
            //->where('mercantile_transactions.Processed', '=', '0')
            ->paginate(20),

            'capitecQuery' => DB::table('mercantile_user_policies')
            ->join('mercantile_transactions', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_transactions.policy_id')
            ->join('mercantile_users', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_users.policy_id')
            ->join('mercantile_user_banks', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_user_banks.policy_id')
            ->where('mercantile_user_banks.UserBankType', '=', 'Capitec')
            //->where('mercantile_transactions.Processed', '=', '0')
            ->paginate(20)
        ]);

        
        //return view('Mercantile.file-export-mercantile-index');

        /*
        $namibia_table = FileImportNamibia::latest()->paginate(15);
        return view('Mercantile.file-export-mercantile-index',compact('namibia_table'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
        */
    }

    public function fileExportMercantileNedbank(Request $request)
    {
        dd('Nedbank');
    }
    public function fileExportMercantileCapitec(Request $request)
    {
        $actionDateFrom = $request->actionDateFrom;
        $actionDateTo = $request->actionDateTo;

        if (!$actionDateFrom || !$actionDateTo) {

            return view('Mercantile.file-export-mercantile-index', [
                'nedbankQuery' => DB::table('mercantile_user_policies')
                ->join('mercantile_transactions', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_transactions.policy_id')
                ->join('mercantile_users', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_users.policy_id')
                ->join('mercantile_user_banks', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_user_banks.policy_id')
                ->where('mercantile_user_banks.UserBankType', '=', 'Nedbank')
                ->where('mercantile_transactions.Processed', '=', '0')
                ->paginate(30),
    
                'capitecQuery' => DB::table('mercantile_user_policies')
                ->join('mercantile_transactions', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_transactions.policy_id')
                ->join('mercantile_users', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_users.policy_id')
                ->join('mercantile_user_banks', 'mercantile_user_policies.PolicyNumber', '=', 'mercantile_user_banks.policy_id')
                ->where('mercantile_user_banks.UserBankType', '=', 'Capitec')
                ->where('mercantile_transactions.Processed', '=', '0')
                ->paginate(30)
            ])->withErrors(['msg' => 'Please select both Action Dates, from and to']);
        }
        
        DB::table('export_fields')->delete();
        $values = array('dateField_1' => $actionDateFrom,'dateField_2' => $actionDateTo);
        DB::table('export_fields')->insert($values);
        
        $downloadDocName = 'CapitecExport_'.date("Y_m_d").'.xlsx';
        return Excel::download(new MercantileCapitecExport, $downloadDocName);
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

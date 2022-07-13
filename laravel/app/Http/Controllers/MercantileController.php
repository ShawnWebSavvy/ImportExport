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

class MercantileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mercantileIndex(){
        
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

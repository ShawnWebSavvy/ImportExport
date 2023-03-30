<?php

namespace App\Console\Commands;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use File;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationEmail;
use App\Exports\TransactionExport;
use App\Models\Deduction;

class test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test';

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


        $deductionsArray = Deduction::all();
        //$deductionsArray = (['1','1','1','1','1','1']);
        $myArray = array();
        //$string = '';
        foreach ($deductionsArray as $deduction) {
            //dd($deduction);
            //$qq = $deduction->row.'\n';
            //array_push($myArray, $deduction->row.'\n');
            //array_push($myArray, '\n');
            array_push($myArray, $deduction);
            //$string = $string . $qq;
            //$string .= $deduction . '\n';

        }
        //file_put_contents('test111.txt', '');

        //dd($myArray);
        //dd($myArray);
        //Storage::put('file.txt', 'Your name');
        //file_put_contents(Storage::put('test111.txt'), $myArray);
        //file_put_contents(Storage::put('downloads/mercantile/current/Test_'.date("Y_m_d").'.txt', 'Test'), $myArray);

        //file_put_contents('test111.txt', $string);
        //file_put_contents('test111.txt', $myArray);

        //file_put_contents('test111.txt', $string."\r\n");

        /*
        $myfile = fopen("test111.txt", "r") or die("Unable to open file!");
        $txt = "John Doe\n";
        fwrite($myfile, $txt);
        fclose($myfile);
        */
        //File::put($destinationPath.$file,$data);

        
        //Storage::disk('local')->put('testfile.txt', '');
        
        //$file = Storage::disk('local');
        // /var/www/html/rocket-switch/storage/app/downloads/mercantile/current
        
        //$path = 'a_testfile.txt';
        ///////////////////////////////////////////
        /*
        $path = '../storage/app/downloads/mercantile/current/testfile.txt';

        $file = fopen($path,"w");
        //fwrite($file,"ttt");
        foreach ($myArray as $row) {
            //dd($row->row);
            fwrite($file, $row->row."\r\n");
        }

        fclose($file);
        */
        ////////////////////////////////////////////////


        //Storage::disk('local')->put('file.txt', 'Contents');
        //Storage::disk('local')->put('file.txt', 'qwerty');

        $path = 'aaa_testfile.txt';
        $file = fopen($path,"w");

        File::move($path, storage_path('app/downloads/mercantile/archive/'.$path));

        /*
        $files = Storage::files("downloads/mercantile/current/");
        foreach ($files as $value) {
            $file_name = str_replace('downloads/mercantile/current/','', $value);
            File::move(storage_path('app/downloads/mercantile/current/'.$file_name), storage_path('app/downloads/mercantile/archive/'.$file_name));
        }

        $path = Storage::files("downloads/mercantile/current/aaa_testfile.txt");
        //$path = 'aaa_testfile.txt';
        $file = fopen($path,"w");
        fwrite($file, 'qwqwqw'."\r\n");
        fclose($file);
        */


        /*
        $deductionsArray = DB::table('deductions')
        ->orderBy('id')
        ->get();
        foreach ($deductionsArray as $row) {
            Storage::append('downloads/mercantile/current//Test_'.date("Y_m_d").'.txt', $row->row);
        }
        */
        //Storage::put('downloads/mercantile/current/Test_'.date("Y_m_d").'.txt', 'Test');

        /*
        DB::table('deductions')
        ->orderBy('id')
        ->chunk(4096, function ($deductions) {
            foreach ($deductions as $deduction) {
                Storage::append('downloads/mercantile/current//Test_'.date("Y_m_d").'.txt', $deduction->row);
            }
        });

        Mail::to('shawnw@leza.co.za')->send(new NotificationEmail());

        DB::table('deductions')->delete();
        */
        /*
       
        Mail::to('shawnw@leza.co.za')->send(new NotificationEmail());
        DB::table('deductions')->delete();
        */

        









    }
}

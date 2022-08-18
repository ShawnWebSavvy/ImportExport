<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use File;
use Zip;
use ZipArchive;
use Illuminate\Support\Facades\Storage;

class Downloads extends Controller
{
    public function mercantileDownloads()
    {
        $type = 'mercantileDownloads';
        $files = Storage::files("downloads/mercantile/current/");
        $arrayFile = [];
        foreach($files as $f){
            $file = str_replace('downloads/mercantile/current/','', $f);
            $file = str_replace('.txt','', $file);
            array_push($arrayFile, $file);
        }
        return view('downloads.downloadsPage',compact('arrayFile'))->with('type', $type);
    }

    public function download(Request $request){
        //return Zip::create('MMMercantile.zip', File::files(storage_path('app/downloads/mercantile/current/')));
        // zip folder using terminal
        $filepath = storage_path('app/downloads/mercantile/current');
        $zip_file = storage_path('app/downloads/mercantile/zip/Mercantile.zip');
        $cmd = 'zip -r ' . $zip_file . ' ' . $filepath;
        passthru($cmd, $err);

        // move files from current download file to archive folder
        $files = Storage::files("downloads/mercantile/current/");
        foreach ($files as $value) {
            //$filename = str_replace('downloads/mercantile/current/','', $value);
            //File::move(storage_path('app/downloads/mercantile/current/'.$filename), storage_path('app/downloads/mercantile/archive/'.$filename));
        }

        // download the file
        return response()->download($zip_file);
        // zip -r drugs.zip current   







        //return redirect('file-import');



        /*
        //$file = "test.txt";
        $file = storage_path('app/downloads/mercantile/current/MercantileCapitec_2022_08_17.txt');

        // Name of the gz file we're creating
        //$gzfile = "test.gz";
        $gzfile = storage_path('app/downloads/mercantile/test.gz');
        // Open the gz file (w9 is the highest compression)
        $fp = gzopen ($gzfile, 'w9');

        // Compress the file
        gzwrite ($fp, file_get_contents($file));

        // Close the gz file and we're done
        gzclose($fp);
        */
        /*
        if ($request->file_type == 'mercantileDownloads'){
            return Zip::create('Mercantile.zip', File::files(storage_path('app/downloads/mercantile/current/')));
        }
        */
    }
}

/*
<?php
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachement; filename=myfile.zip");
passthru("zip -r -0 - /stuff/to/zip/");
exit();
?>

*/
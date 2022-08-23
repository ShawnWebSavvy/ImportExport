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
        $currentDownloads = [];
        foreach($files as $f){
            $file = str_replace('downloads/mercantile/current/','', $f);
            $file = str_replace('.txt','', $file);
            array_push($currentDownloads, $file);
        }

        $files = Storage::files("downloads/mercantile/archive/");
        $archiveDownloads = [];
        foreach($files as $f){
            $file = str_replace('downloads/mercantile/archive/','', $f);
            $file = str_replace('.txt','', $file);
            array_push($archiveDownloads, $file);
        }

        return view('downloads.downloadsPage',compact('currentDownloads', 'archiveDownloads'))->with('type', $type);
    }

    public function download(Request $request){
        // zip folder using terminal - passthru
        $filepath = storage_path('app/downloads/mercantile/current');
        $zip_file = storage_path('app/downloads/mercantile/zip/Mercantile.zip');
        $cmd = '-rm --force --dir --recursive ' . $zip_file . ' ' . $filepath;
        passthru($cmd, $err);

        // move files from current download file to archive folder
        $files = Storage::files("downloads/mercantile/current/");
        foreach ($files as $value) {
            $filename = str_replace('downloads/mercantile/current/','', $value);
            File::move(storage_path('app/downloads/mercantile/current/'.$filename), storage_path('app/downloads/mercantile/archive/'.$filename));
        }

        // download the file
        return response()->download($zip_file);
    }

    public function downloadArchive($filename){
        $path = storage_path('app/downloads/mercantile/archive/'.$filename.'.txt');
        return response()->download($path);
    }
} 
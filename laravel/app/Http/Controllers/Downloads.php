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
        if ($request->file_type == 'mercantileDownloads'){
            return Zip::create('Mercantile.zip', File::files(storage_path('app/downloads/mercantile/current/')));
        }
        
    }
}

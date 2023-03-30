@extends('layouts.header') 

<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h4>Downloads</h4>
                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <h5>Current Download/s</h5>
                            
                            Files available for download: 
                            </br>
                            <!--
                            @ foreach ($currentDownloads as $filename)
                                {  { $filename }  }, 
                            @ endforeach
                            
                            <form action="{{ route('download') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="file_type" value="{{ $type }}">
                                    <button class="btn btn-success">Download Files</button>
                            </form>
                            -->
                            @foreach ($currentDownloads as $filename)
                                <a href="{{ route('downloadCurrent',$filename) }}">{{ $filename }}</a>; </br>
                            @endforeach
                        </div>
                        <div class="col-sm-6">
                             <h5>Rejection Download</h5>
                             @foreach ($rejectionsCurrentDownloads as $filename)
                                <a href="{{ route('rejectionsCurrent',$filename) }}">{{ $filename }}</a>; </br>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <h5>Archived Downloads</h5>
                            Archived files available for download: 
                            </br>
                            @foreach ($archiveDownloads as $filename)
                                <a href="{{ route('downloadArchive',$filename) }}">{{ $filename }}</a>; </br>
                            @endforeach
                        </div>
                        <div class="col-sm-6">
                        <h5>Rejection Archive</h5>
                            @foreach ($rejectionsArchiveDownloads as $filename)
                                <a href="{{ route('rejectionsArchive',$filename) }}">{{ $filename }}</a>; </br>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

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
                        <h5>Current Download/s</h5>
                        
                        Files available for download: 
                        </br>
                        @foreach ($arrayFile as $filename)
                            {{ $filename }}, 
                        @endforeach
                        
                        <form action="{{ route('download') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="file_type" value="{{ $type }}">
                                <button class="btn btn-success">Download Files</button>
                        </form>
                    </div>
                    <div class="col-sm-12">
                        <h5>Archived Downloads</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

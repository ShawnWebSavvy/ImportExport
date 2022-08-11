@extends('layouts.header') 

<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                <div class="container mt-5 text-center">
                    <!--
                    <h4>View Imported Data</h4>
                    <h5>Namibia</h5>
                    <a class="btn btn-success" href="{{ route('file-export-namibia-index') }}">Namibia</a>
                    <h5>Botswana</h5>
                    <a class="btn btn-success" href="{{ route('file-export-botswana-index') }}">Botswana</a>
                    -->
                    <!--
                    <h5>Botswana</h5>
                    <a class="btn btn-success" href="{{ route('file-export-botswana-install-headers-index') }}">Install Header</a>
                    <a class="btn btn-success" href="{{ route('file-export-botswana-user-headers-index') }}">User Header</a>
                    <a class="btn btn-success" href="{{ route('file-export-botswana-contras-index') }}">Contras</a>
                    <a class="btn btn-success" href="{{ route('file-export-botswana-transactions-index') }}">Transactions</a>
                    <a class="btn btn-success" href="{{ route('file-export-botswana-install-trailers-index') }}">Install Trailers</a>
                    <a class="btn btn-success" href="{{ route('file-export-botswana-user-trailers-index') }}">User Trailers</a>
                    -->
                </div>

                <div class="container mt-5 text-left">
                
                    <form action="{{ route('file-upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="container mt-5 text-left">
                                <div class="row">
                                    <div class="col-md-2">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="banks">Upload Type</label><br>

                                                <input type="radio" id="Capitec" name="file_type" value="Capitec" checked='checked'>
                                                <label for="Capitec">Capitec POC</label><br>

                                                <input type="radio" id="CapitecRejections" name="file_type" value="CapitecRejections">
                                                <label for="CapitecRejections">Capitec Rejections</label><br>

                                                <input type="radio" id="Botswana" name="file_type" value="Botswana">
                                                <label for="Botswana">Botswana</label><br>

                                                <input type="radio" id="Namibia" name="file_type" value="Namibia">
                                                <label for="Namibia">Namibia</label><br>

                                                
                                                <!--
                                                <input type="radio" id="BotswanaInstallHeaderRecord" name="file_type" value="BotswanaInstallHeaderRecord">
                                                <label for="BotswanaInstallHeaderRecord">BotswanaInstallHeaderRecord</label><br>
                                                <input type="radio" id="BotswanaUserHeaderRecord" name="file_type" value="BotswanaUserHeaderRecord">
                                                <label for="BotswanaUserHeaderRecord">BotswanaUserHeaderRecord</label><br>
                                                <input type="radio" id="BotswanaTransactions" name="file_type" value="BotswanaTransactions">
                                                <label for="BotswanaTransactions">BotswanaTransactions</label><br>
                                                <input type="radio" id="BotswanaContras" name="file_type" value="BotswanaContras">
                                                <label for="BotswanaContras">BotswanaContras</label><br>
                                                <input type="radio" id="BotswanaUserTrailers" name="file_type" value="BotswanaUserTrailers">
                                                <label for="BotswanaUserTrailers">BotswanaUserTrailers</label><br>
                                                <input type="radio" id="BotswanaInstallTrailers" name="file_type" value="BotswanaInstallTrailers">
                                                <label for="BotswanaInstallTrailers">BotswanaInstallTrailers</label><br>
                                                -->
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group mb-4" style="max-width: 500px; margin: 0 auto;">
                                                    <div class="custom-file text-left">
                                                        <input type="file" name="file" class="custom-file-input" id="customFile">
                                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                                    </div>
                                                    <button class="btn btn-primary">Import data</button>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--
                    </br></br></br></br>
                    <form action="{{ route('file-export-botswana-totext') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        Botswana XLSX to Text
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-4" style="max-width: 500px; margin: 0 auto;">
                                            <div class="custom-file text-left">
                                                <input type="file" name="file" class="custom-file-input" id="customFile">
                                                <label class="custom-file-label" for="customFile">Choose file</label>
                                            </div>
                                            <button class="btn btn-primary">Import data</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    -->
                    <div class="row">
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-8">
                            @if($errors->any())
                                <h5>{{$errors->first()}}</h5>
                            @endif
                        </div>
                    </div>

                </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>





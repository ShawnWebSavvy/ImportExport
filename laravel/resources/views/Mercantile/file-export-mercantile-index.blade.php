@extends('layouts.header') 

<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h4>Mercantile Records</h4>
                    <div class="row">
                        <div class="col-sm-6">
                            <form action="{{ route('file-export-mercantile-nedbank') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <h5>Nedbank Records</h5>
                                    </br>
                                    <button class="btn btn-success">Download</button>
                            </form>
                            </br></br>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Policy Number</th>
                                    <th>Full Name</th>
                                    <th>Action Date</th>
                                </tr>
                                @foreach ($nedbankQuery as $row)
                                <tr>
                                    <td>{{ $row->PolicyNumber }}</td>
                                    <td>{{ $row->AccountHolderFullName }}</td>
                                    <td>{{ $row->ActionDate }}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    
                        <div class="col-sm-6">
                                <form action="{{ route('file-export-mercantile-capitec') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <h5>Capitec Records</h5>
                                        </br>
                                        <label for="actionDate">Action Date</label>
                                        <input type="date" id="actionDateFrom" name="actionDateFrom">
                                        <label for="actionDate">:</label>
                                        <input type="date" id="actionDateTo" name="actionDateTo">
                                        <button class="btn btn-success">Download</button>

                                        <div>
                                        @if($errors->any())
                                            <h5>{{$errors->first()}}</h5>
                                        @endif
                                        <div>
                                        
                                </form>
                                </br></br>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Policy Number</th>
                                        <th>Full Name</th>
                                        <th>Action Date</th>
                                    </tr>
                                    @foreach ($capitecQuery as $row)
                                    <tr>
                                        <td>{{ $row->PolicyNumber }}</td>
                                        <td>{{ $row->AccountHolderFullName }}</td>
                                        <td>{{ $row->ActionDate }}</td>
                                    </tr>
                                    @endforeach
                                </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    {!! $nedbankQuery->links() !!} &nbsp; Nedbank
                </div>
                <div class="row">
                    {!! $capitecQuery->links() !!} &nbsp; Capitec
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

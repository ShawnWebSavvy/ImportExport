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
                                    <th>Policy</th>
                                    <th>Action Date</th>
                                    <th>User Full Name</th>
                                    <th>Transaction</th>
                                </tr>
                                @foreach ($nedbankQuery as $row)
                                <tr>
                                    <td>{{ $row->PolicyNumber }}</td>
                                    <td>{{ $row->ActionDate }}</td>
                                    <td>{{ $row->AccountHolderFullName }}</td>
                                    <td>{{ $row->TransactionUniqueID }}</td>
                                </tr>
                                @endforeach
                            </table>
                            {!! $nedbankQuery->links() !!}
                        </div>
                    
                        <div class="col-sm-6">
                                <form action="{{ route('file-export-mercantile-nedbank') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <h5>Capitec Records</h5>
                                        </br>
                                        <label for="actionDate">Action Date</label>
                                        <input type="date" id="actionDateFrom" name="actionDateFrom">
                                        <label for="actionDate">:</label>
                                        <input type="date" id="actionDateTo" name="actionDateTo">
                                        <button class="btn btn-success">Download</button>
                                </form>
                                </br></br>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Policy</th>
                                        <th>Action Date</th>
                                        <th>User Full Name</th>
                                        <th>Transaction</th>
                                    </tr>
                                    @foreach ($capitecQuery as $row)
                                    <tr>
                                        <td>{{ $row->PolicyNumber }}</td>
                                        <td>{{ $row->ActionDate }}</td>
                                        <td>{{ $row->AccountHolderFullName }}</td>
                                        <td>{{ $row->TransactionUniqueID }}</td>
                                    </tr>
                                    @endforeach
                                </table>
                                {!! $capitecQuery->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@extends('layouts.header') 

<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <form action="{{ route('file-export-botswana') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <h4>Botswana Records</h4>
                            </br>
                            <!--
                            <label for="accountNumber">Account Number</label>
                            <input type="number" id="accountNumber" name="accountNumber">

                            <label for="actionDate">Date:</label>
                            <input type="date" id="actionDate" name="actionDate">
                            -->
                            <label for="actionDate">Action Date From:</label>
                            <input type="date" id="actionDateFrom" name="actionDateFrom">
                            <label for="actionDate">To:</label>
                            <input type="date" id="actionDateTo" name="actionDateTo">

                            <button class="btn btn-success">Download</button>
                            <a class="btn btn-danger" href="{{ route('file-delete-namibia') }}">Delete</a>
                            <a class="btn btn-primary" href="{{ route('file-import') }}">Back</a>
                    </form>
                    </br></br>
                    <table class="table table-bordered">
                        <tr>
                            <th>Name</th>
                            <th>Surname</th>
                            <th>ID Number</th>
                            <th>Branch</th>
                            <th>Acc No.</th>
                            <th>Non Standard Acc No.</th>
                            <th>Acc Type</th>
                            <th>Acc Ref</th>

                            <th>Amount</th>
                            <th>Policy No.</th>
                            <th>Action Date</th>
                            <th>Batch ID</th>
                            
                            
                        </tr>
                        @foreach ($table as $row)
                        <tr>
                            <td>{{ $row->RecipientAccountHolderName }}</td>
                            <td>{{ $row->RecipientAccountHolderSurname }}</td>
                            <td>{{ $row->RecipientID }}</td>
                            <td>{{ $row->BranchCode }}</td>
                            <td>{{ $row->RecipientAccountNumber }}</td>
                            <td>{{ $row->RecipientNonStandardAccountNumber }}</td>
                            <td>{{ $row->RecipientAccountType }}</td>
                            <td>{{ $row->AccountReference }}</td>
                            <td>{{ $row->RecipientAmount }}</td>
                            <td>{{ $row->PolicyNumber }}</td>
                            <td>{{ $row->ActionDate }}</td>
                            <td>{{ $row->Guid }}</td>
                        </tr>
                        @endforeach
                    </table>
                    {!! $table->links() !!}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

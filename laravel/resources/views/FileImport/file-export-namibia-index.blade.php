@extends('layouts.header')

@section('content')
<form action="{{ route('file-export-namibia') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <h4>Namibia Records</h4>
        <!--
        <label for="accountNumber">Account Number</label>
        <input type="number" id="accountNumber" name="accountNumber">

        <label for="actionDate">Date:</label>
        <input type="date" id="actionDate" name="actionDate">
        -->
        <label for="actionDate">Action Date:</label>
        <input type="date" id="actionDate" name="actionDate">

        <button class="btn btn-success">Download</button>
        <a class="btn btn-danger" href="{{ route('file-delete-namibia') }}">Delete</a>
        <a class="btn btn-primary" href="{{ route('file-import') }}">Back</a>
</form>
<table class="table table-bordered">
    <tr>
        <th>Name</th>
        <th>Account Number</th>
        <th>Action Date</th>
        <th>Account Type</th>
        <th>Bic Code</th>
        <th>Amount</th>
        <th>Contract Reference</th>
        <th>Tracking</th>
        <th>Abbreviated Name</th>
        <th>Collection</th>
        <th>Batch ID</th>
    </tr>
    @foreach ($namibia_table as $row)
    <tr>
        <td>{{ $row->RecipientAccountHolderName }}</td>
        <td>{{ $row->RecipientAccountNumber }}</td>
        <td>{{ $row->ActionDate }}</td>
        <td>{{ $row->RecipientAccountType }}</td>
        <td>{{ $row->BranchSwiftBicCode }}</td>
        <td>{{ $row->RecipientAmount }}</td>
        <td>{{ $row->ContractReference }}</td>
        <td>{{ $row->Tracking }}</td>
        <td>{{ $row->RecipientAccountHolderAbbreviatedName }}</td>
        <td>{{ $row->CollectionReason }}</td>
        <td>{{ $row->batch_number }}</td>
    </tr>
    @endforeach
</table>
{!! $namibia_table->links() !!}
@endsection

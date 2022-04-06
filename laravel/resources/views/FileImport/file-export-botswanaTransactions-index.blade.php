Botswana Transactions
@extends('layouts.header')

@section('content')
<form action="{{ route('file-export-botswana-transactions') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <!--
        <label for="accountNumber">Account Number</label>
        <input type="number" id="accountNumber" name="accountNumber">

        <label for="actionDate">Date:</label>
        <input type="date" id="actionDate" name="actionDate">
        -->
        <label for="actionDate">Action Date:</label>
        <input type="date" id="actionDate" name="actionDate">

        <button class="btn btn-success">Download</button>
        <a class="btn btn-danger" href="{{ route('file-delete-namibia') }}">DeleteX</a>
        <a class="btn btn-primary" href="{{ route('file-import') }}">Back</a>
</form>
<table class="table table-bordered">
    <tr>
        <th>Record</th>
        <th>User Branch</th>
        <th>User Acc No.</th>
        <th>User Code</th>
        <th>Sequence No.</th>
        <th>Homing Branch</th>
        <th>Homing Acc No.</th>
        <th>Acc Type</th>
        <th>Amount</th>
        <th>Action Date</th>
        <th>Entry</th>
        <th>Tax Code</th>
        <th>Abbreviated Name</th>
        <th>Reference</th>
        <th>Homing Acc Name</th>
        <th>Non Std Acc No.</th>
        <th>Homing Institution</th>
    </tr>
    @foreach ($table as $row)
    <tr>
        <td>{{ $row->RecordIdentifier }}</td>
        <td>{{ $row->UserBranch }}</td>
        <td>{{ $row->UserAccountNumber }}</td>
        <td>{{ $row->UserCode }}</td>
        <td>{{ $row->SequenceNumber }}</td>
        <td>{{ $row->HomingBranch }}</td>
        <td>{{ $row->HomingAccountNumber }}</td>
        <td>{{ $row->AccountType }}</td>
        <td>{{ $row->Amount }}</td>
        <td>{{ $row->ActionDate }}</td>
        <td>{{ $row->EntryType }}</td>
        <td>{{ $row->TaxCode }}</td>
        <td>{{ $row->UserAbbreviatedName }}</td>
        <td>{{ $row->UserReference }}</td>
        <td>{{ $row->HomingAccountName }}</td>
        <td>{{ $row->NonStandardAccountNumber }}</td>
        <td>{{ $row->HomingInstitution }}</td>
    </tr>
    @endforeach
</table>
{!! $table->links() !!}
@endsection

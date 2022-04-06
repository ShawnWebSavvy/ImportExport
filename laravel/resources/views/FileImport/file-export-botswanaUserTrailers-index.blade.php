Botswana User Trailers
@extends('layouts.header')

@section('content')
<form action="{{ route('file-export-botswana-user-trailers') }}" method="POST" enctype="multipart/form-data">
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
        <th>User Code</th>
        <th>Sequence No. First</th>
        <th>Last</th>
        <th>Action Date First</th>
        <th>Last</th>
        <th>No. Debit Records</th>
        <th>No. Credit Records</th>
        <th>No. Contra Records</th>
        <th>Total Debit</th>
        <th>Total Credit</th>
        <th># Total Homing Acc No.</th>
        <th></th>
    </tr>
    @foreach ($table as $row)
    <tr>
        <td>{{ $row->RecordIdentifier }}</td>
        <td>{{ $row->UserCode }}</td>
        <td>{{ $row->SequenceNumberFirst }}</td>
        <td>{{ $row->SequenceNumberLast }}</td>
        <td>{{ $row->ActionDateFirst }}</td>
        <td>{{ $row->ActionDateLast }}</td>
        <td>{{ $row->NumberDebitRecords }}</td>
        <td>{{ $row->NumberCreditRecords }}</td>
        <td>{{ $row->NumberContraRecords }}</td>
        <td>{{ $row->TotalDebitValue }}</td>
        <td>{{ $row->TotalCreditValue }}</td>
        <td>{{ $row->HashTotalofHomingAccountNumbers }}</td>
    </tr>
    @endforeach
</table>
{!! $table->links() !!}
@endsection

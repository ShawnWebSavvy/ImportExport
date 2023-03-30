Botswana User Header
@extends('layouts.header')

@section('content')
<form action="{{ route('file-export-botswana-user-headers') }}" method="POST" enctype="multipart/form-data">
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
        <th>Creation Date</th>
        <th>Purge Date</th>
        <th>Action Date First</th>
        <th>Last</th>
        <th>Sequence No.</th>
        <th>Generation No.</th>
        <th>Service</th>
    </tr>
    @foreach ($table as $row)
    <tr>
        <td>{{ $row->RecordIdentifier }}</td>
        <td>{{ $row->UserCode }}</td>
        <td>{{ $row->CreationDate }}</td>
        <td>{{ $row->PurgeDate }}</td>
        <td>{{ $row->ActionDateFirst }}</td>
        <td>{{ $row->ActionDateLast }}</td>
        <td>{{ $row->SequenceNumber }}</td>
        <td>{{ $row->GenerationNumber }}</td>
        <td>{{ $row->Service }}</td>
    </tr>
    @endforeach
</table>
{!! $table->links() !!}
@endsection

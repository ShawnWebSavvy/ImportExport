Botswana Install Trailers
@extends('layouts.header')

@section('content')
<form action="{{ route('file-export-botswana-install-trailers') }}" method="POST" enctype="multipart/form-data">
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
        <th>Volume No.</th>
        <th>Tape Serial No.</th>
        <th>Installation ID from</th>
        <th>To</th>
        <th>Creation Date</th>
        <th>Purge Date</th>
        <th>Generation No.</th>
        <th>Block Length</th>
        <th>Record Length</th>
        <th>Service</th>
        <th>Block Count</th>
        <th>Record Count</th>
        <th>Header Trailer Count</th>
    </tr>
    @foreach ($table as $row)
    <tr>
        <td>{{ $row->RecordIdentifier }}</td>
        <td>{{ $row->VolumeNumber }}</td>
        <td>{{ $row->TapeSerialNumber }}</td>
        <td>{{ $row->InstallationIDfrom }}</td>
        <td>{{ $row->InstallationIDto }}</td>
        <td>{{ $row->CreationDate }}</td>
        <td>{{ $row->PurgeDate }}</td>
        <td>{{ $row->GenerationNumber }}</td>
        <td>{{ $row->BlockLength }}</td>
        <td>{{ $row->RecordLength }}</td>
        <td>{{ $row->Service }}</td>
        <td>{{ $row->BlockCount }}</td>
        <td>{{ $row->RecordCount }}</td>
        <td>{{ $row->UserHeaderTrailerCount }}</td>
    </tr>
    @endforeach
</table>
{!! $table->links() !!}
@endsection

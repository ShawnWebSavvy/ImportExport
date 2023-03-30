<form action="{{ route('file-export-namibia') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <!--
        <label for="accountNumber">Account Number</label>
        <input type="number" id="accountNumber" name="accountNumber">
        
        <label for="actionDate">Date:</label>
        <input type="date" id="actionDate" name="actionDate">
        -->
        <button class="btn btn-primary">Export</button>
</form>
<a class="btn btn-success" href="{ { route('file-export') }}">Delete</a>
<a class="btn btn-success" href="{{ route('file-import') }}">Import</a>
<table class="table table-bordered">
    <tr>
        <th>Name</th>
        <th>Account Number</th>
        <th>Account Type</th>
        <th>Bic Code</th>
        <th>Amount</th>
        <th>Contract Reference</th>
        <th>Tracking</th>
        <th>Abbreviated Name</th>
        <th>Collection</th>
        <th width="400px">Action</th>
    </tr>
    @foreach ($namibia_table as $row)
    <tr>
        <td>{{ $row->RecipientAccountHolderName }}</td>
        <td>{{ $row->RecipientAccountNumber }}</td>
        <td>{{ $row->RecipientAccountType }}</td>
        <td>{{ $row->BranchSwiftBicCode }}</td>
        <td>{{ $row->RecipientAmount }}</td>
        <td>{{ $row->ContractReference }}</td>
        <td>{{ $row->Tracking }}</td>
        <td>{{ $row->RecipientAccountHolderInitials }}</td>
        <td>{{ $row->CollectionReason }}</td>
    </tr>
    @endforeach
</table>
{!! $namibia_table->links() !!}


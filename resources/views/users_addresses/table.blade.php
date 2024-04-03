<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="users-addresses-table">
            <thead>
            <tr>
                <th>Area Id</th>
                <th>Address</th>
                <th>Flat</th>
                <th>Place</th>
                <th>Phone</th>
                <th>User Id</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($usersAddresses as $usersAddresses)
                <tr>
                    <td>{{ $usersAddresses->area_id }}</td>
                    <td>{{ $usersAddresses->address }}</td>
                    <td>{{ $usersAddresses->flat }}</td>
                    <td>{{ $usersAddresses->place }}</td>
                    <td>{{ $usersAddresses->phone }}</td>
                    <td>{{ $usersAddresses->user_id }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['usersAddresses.destroy', $usersAddresses->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('usersAddresses.show', [$usersAddresses->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('usersAddresses.edit', [$usersAddresses->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-edit"></i>
                            </a>
                            {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                        </div>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer clearfix">
        <div class="float-right">
            @include('adminlte-templates::common.paginate', ['records' => $usersAddresses])
        </div>
    </div>
</div>

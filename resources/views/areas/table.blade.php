<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="areas-table">
            <thead>
            <tr>
                <th>Name En</th>
                <th>Name Ar</th>
                <th>Status</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($areas as $areas)
                <tr>
                    <td>{{ $areas->name_en }}</td>
                    <td>{{ $areas->name_ar }}</td>
                    <td>{{ $areas->status }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['areas.destroy', $areas->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('areas.show', [$areas->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('areas.edit', [$areas->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $areas])
        </div>
    </div>
</div>

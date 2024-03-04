<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="categories-table">
            <thead>
            <tr>
                <th>Name En</th>
                <th>Name Ar</th>
                <th>Has Parent</th>
                <th>Parent Id</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($categories as $category)
                <tr>
                    <td>{{ $category->name_en }}</td>
                    <td>{{ $category->name_ar }}</td>
                    <td>{{ $category->has_parent }}</td>
                    <td>{{ $category->parent_id }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['categories.destroy', $category->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('categories.show', [$category->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('categories.edit', [$category->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $categories])
        </div>
    </div>
</div>

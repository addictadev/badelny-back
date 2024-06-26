<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="products-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Category Id</th>
                <th>Sub Category Id</th>
                <th>Wight</th>
                <th>Condition</th>
                <th>Color</th>
                <th>Exchange Options</th>
                <th>Price</th>
                <th>Points</th>
                <th>Description</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category_id }}</td>
                    <td>{{ $product->sub_category_id }}</td>
                    <td>{{ $product->wight }}</td>
                    <td>{{ $product->condition }}</td>
                    <td>{{ $product->color }}</td>
                    <td>{{ $product->exchange_options }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->points }}</td>
                    <td>{{ $product->description }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['products.destroy', $product->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('products.show', [$product->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('products.edit', [$product->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $products])
        </div>
    </div>
</div>

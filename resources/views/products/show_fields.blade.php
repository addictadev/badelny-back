<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $product->name }}</p>
</div>

<!-- Category Id Field -->
<div class="col-sm-12">
    {!! Form::label('category_id', 'Category Id:') !!}
    <p>{{ $product->category_id }}</p>
</div>

<!-- Sub Category Id Field -->
<div class="col-sm-12">
    {!! Form::label('sub_category_id', 'Sub Category Id:') !!}
    <p>{{ $product->sub_category_id }}</p>
</div>

<!-- Wight Field -->
<div class="col-sm-12">
    {!! Form::label('wight', 'Wight:') !!}
    <p>{{ $product->wight }}</p>
</div>

<!-- Condition Field -->
<div class="col-sm-12">
    {!! Form::label('condition', 'Condition:') !!}
    <p>{{ $product->condition }}</p>
</div>

<!-- Color Field -->
<div class="col-sm-12">
    {!! Form::label('color', 'Color:') !!}
    <p>{{ $product->color }}</p>
</div>

<!-- Exchange Options Field -->
<div class="col-sm-12">
    {!! Form::label('exchange_options', 'Exchange Options:') !!}
    <p>{{ $product->exchange_options }}</p>
</div>

<!-- Price Field -->
<div class="col-sm-12">
    {!! Form::label('price', 'Price:') !!}
    <p>{{ $product->price }}</p>
</div>

<!-- Points Field -->
<div class="col-sm-12">
    {!! Form::label('points', 'Points:') !!}
    <p>{{ $product->points }}</p>
</div>

<!-- Description Field -->
<div class="col-sm-12">
    {!! Form::label('description', 'Description:') !!}
    <p>{{ $product->description }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $product->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $product->updated_at }}</p>
</div>


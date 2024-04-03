<!-- From Field -->
<div class="col-sm-12">
    {!! Form::label('from', 'From:') !!}
    <p>{{ $order->from }}</p>
</div>

<!-- Request Id Field -->
<div class="col-sm-12">
    {!! Form::label('request_id', 'Request Id:') !!}
    <p>{{ $order->request_id }}</p>
</div>

<!-- Bayer Product Id Field -->
<div class="col-sm-12">
    {!! Form::label('bayer_product_id', 'Bayer Product Id:') !!}
    <p>{{ $order->bayer_product_id }}</p>
</div>

<!-- Points Field -->
<div class="col-sm-12">
    {!! Form::label('points', 'Points:') !!}
    <p>{{ $order->points }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $order->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $order->updated_at }}</p>
</div>


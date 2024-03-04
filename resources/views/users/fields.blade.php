<!-- Name En Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name_en', 'Name En:') !!}
    {!! Form::text('name_en', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Name Ar Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name_ar', 'Name Ar:') !!}
    {!! Form::text('name_ar', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Phone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('phone', 'Phone:') !!}
    {!! Form::text('phone', null, ['class' => 'form-control']) !!}
</div>

<!-- Gender Field -->
<div class="form-group col-sm-6">
    {!! Form::label('gender', 'Gender:') !!}
    {!! Form::text('gender', null, ['class' => 'form-control']) !!}
</div>

<!-- Password Field -->
<div class="form-group col-sm-6">
    {!! Form::label('password', 'Password:') !!}
    {!! Form::text('password', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Area En Field -->
<div class="form-group col-sm-6">
    {!! Form::label('area_en', 'Area En:') !!}
    {!! Form::text('area_en', null, ['class' => 'form-control']) !!}
</div>

<!-- Area Ar Field -->
<div class="form-group col-sm-6">
    {!! Form::label('area_ar', 'Area Ar:') !!}
    {!! Form::text('area_ar', null, ['class' => 'form-control']) !!}
</div>

<!-- Address En Field -->
<div class="form-group col-sm-6">
    {!! Form::label('address_en', 'Address En:') !!}
    {!! Form::text('address_en', null, ['class' => 'form-control']) !!}
</div>

<!-- Address Ar Field -->
<div class="form-group col-sm-6">
    {!! Form::label('address_ar', 'Address Ar:') !!}
    {!! Form::text('address_ar', null, ['class' => 'form-control']) !!}
</div>
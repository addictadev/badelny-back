@extends('dashboard.layouts.master')
@section('style')
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
@endsection
@section('content')

            <div class="dashboard-content-one">
                <!-- Breadcubs Area Start Here -->
                <div class="breadcrumbs-area">
                    <h3>{{trans('dashboard.products')}}</h3>
                    <ul>
                        <li>
                            <a href="/">{{trans('dashboard.home')}}</a>
                        </li>
                        <li>{{trans('dashboard.edit')}}</li>
                    </ul>
                </div>
                <!-- Student Table Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                         <h3 style="text-align: center">{{trans('dashboard.product_information')}}</h3>
                        @if(isset($product))
                            <form class="new-added-form" action="{{route('products.update',$product->id)}}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                        @else
                        <form class="new-added-form" action="{{route('products.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            @endif
                            <div class="row">
                                <div class="form-group row">
                                <div class="col-md-10">
                                    <div class="row col-12 form-group">
                                        <label class="col-sm-4">
                                            {{trans('dashboard.Name')}}
                                        </label>
                                        <div class="col-sm-8">
                                            <input type="text" {{isset($product) ? 'disabled' : ''}} value="{{isset($product) ?  $product->name : ''}}"  class="">
                                        </div>
                                    </div>
                                <div class="row col-12 form-group">
                                    <label class="col-sm-4">
                                        {{trans('dashboard.Image')}}
                                    </label>
                                    <div class="col-sm-8">
                                        @isset($product)
                                           <img src="{{$product->getFirstMediaUrl('images', 'thumb')}}" width="120px">
                                         @endif
                                    </div>

                                </div>
                                    <div class="row col-12 form-group">
                                        <label class="col-sm-4">
                                            {{trans('dashboard.Category')}}
                                        </label>
                                        <div class="col-sm-8">
                                            <input type="text" {{isset($product) ? 'disabled' : ''}} value="{{isset($product) ?  $product->category->name_en : ''}}"  class="">
                                        </div>
                                    </div>
                                    <div class="row col-12 form-group">
                                        <label class="col-sm-4">
                                            {{trans('dashboard.weight')}}
                                        </label>
                                        <div class="col-sm-8">
                                            <input type="text" {{isset($product) ? 'disabled' : ''}} value="{{isset($product) ?  $product->weight : ''}}"  class="">
                                        </div>
                                    </div>
                                    <div class="row col-12 form-group">
                                        <label class="col-sm-4">
                                            {{trans('dashboard.color')}}
                                        </label>
                                        <div class="col-sm-8">
                                            <input type="text" {{isset($product) ? 'disabled' : ''}} value="{{isset($product) ?  $product->color : ''}}"  class="">
                                        </div>
                                    </div>
                                    <div class="row col-12 form-group">
                                        <label class="col-sm-4">
                                            {{trans('dashboard.price')}}
                                        </label>
                                        <div class="col-sm-8">
                                            <input type="text" {{isset($product) ? 'disabled' : ''}} value="{{isset($product) ?  $product->price : ''}}"  class="">
                                        </div>
                                    </div>

                                    <div class="row col-12 form-group">
                                        <label class="col-sm-4">
                                            {{trans('dashboard.points')}}
                                        </label>
                                        <div class="col-sm-8">
                                            <input type="text" {{isset($product) ? 'disabled' : ''}} value="{{isset($product) ?  $product->points : ''}}"  class="">
                                        </div>
                                    </div>
                                        <br><br>
                                    <h3 style="text-align: center">{{trans('dashboard.update status and approve for product')}}</h3>
                                    <div class="row col-12 form-group">

                                        <label class="col-sm-4">
                                            {{trans('dashboard.status')}}
                                        </label>

                                        <div class="col-sm-8">
                                            <select name="status" class="form-control">
                                                <option value="0" {{isset($product) && $product->status == 0 ? 'selected' : ''}}> {{trans('dashboard.un publish')}}</option>
                                                <option value="1" {{isset($product) && $product->status == 1 ? 'selected' : ''}}> {{trans('dashboard.publish')}}</option>
                                            </select>
                                        </div>

                                    </div>

                                    <div class="row col-12 form-group">

                                        <label class="col-sm-4">
                                            {{trans('dashboard.Approve')}}
                                        </label>

                                        <div class="col-sm-8">
                                            <select name="is_approve" class="form-control">
                                                <option value="0" {{isset($product) && $product->is_approve == 0 ? 'selected' : ''}}> {{trans('dashboard.un publish')}}</option>
                                                <option value="1" {{isset($product) && $product->is_approve == 1 ? 'selected' : ''}}> {{trans('dashboard.publish')}}</option>
                                            </select>
                                        </div>

                                    </div>
                                <div class="col-12 form-group mg-t-8" style="margin-top: 25px">
                                    <button type="submit" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark">{{trans('dashboard.save')}}</button>
                            </div>
                            </div>
                        </form>

                    </div>
                </div>
                <!-- Student Table Area End Here -->
@endsection
@section('script')

    <script>

        $('#select').prepend("<option value=''>{{trans('dashboard.select_category')}}</option>").val('');
        $('[name="has_parent"]').on('change', function() {
            $('#select').toggle(this.checked);
        }).change();
    </script>
@endsection

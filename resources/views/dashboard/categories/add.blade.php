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
                    <h3>{{trans('dashboard.categories')}}</h3>
                    <ul>
                        <li>
                            <a href="/">{{trans('dashboard.home')}}</a>
                        </li>
                        <li>   <li>{{trans('dashboard.add_category')}}</li>
                    </ul>
                </div>
                <!-- Student Table Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">

                        @if(isset($category))
                            <form class="new-added-form" action="{{route('categories.update',$category->id)}}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                        @else
                        <form class="new-added-form" action="{{route('categories.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            @endif
                            <div class="row">
                                <div class="form-group row">
                                <div class="col-sm-6">
                                    <nav>
                                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">English</button>
                                            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">عربى</button>
                                        </div>
                                    </nav>
                                    <br>

                                    <div class="tab-content" id="nav-tabContent" >
                                        <div class=" col-sm-10 tab-pane fade show active " id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                            <label for="name_en">Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control " name="name_en"  value="{{isset($category) ? $category->name_en : ''}}"  placeholder="Name" required>
                                            @if ($errors->has('name_en'))
                                                <p class="text-danger"> {{ $errors->first('name_en') }} </p>
                                            @endif
                                        </div>

                                        <div class="col-sm-10 tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-home-tab">
                                            <label for="name_ar">الاسم<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control " name="name_ar"  value="{{isset($category) ? $category->name_ar : ''}}"  placeholder="الاسم" required>
                                            @if ($errors->has('name_ar'))
                                                <p class="text-danger"> {{ $errors->first('name_ar') }} </p>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                <div class="row col-12 form-group">
                                    <label class="col-sm-4">
                                        {{trans('dashboard.Image')}} *
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="file" name="image" {{!isset($category) ? 'required' : ''}}  class="">
                                        @if($errors->has('images'))
                                            <div class="alert alert-danger">{{ $errors->first('image') }}</div>
                                        @endif
                                        @isset($category)
                                           <img src="{{$category->getFirstMediaUrl('images', 'thumb')}}" width="120px">
                                         @endif
                                    </div>

                                </div>
                                <div class="row col-12 form-group">
                                    {{trans('dashboard.Has_Parent')}}
                                    <label style="padding-right:0px;margin: 5px" class="switch">
                                        <input type="checkbox" name="has_parent" value="1" {{isset($category) && $category->has_parent == 1 ? 'checked': ''}}>
                                        <span class="slider round"></span>

                                    </label>

                                    <div class="col-sm-8">
                                        <select name="parent_id" class="form-control" id="{{isset($category) && $category->parent_id != null ? '' : 'select'}}">
                                            @foreach($categories as $Category)
                                            <option value="{{$Category->id}}" {{isset($category) && $category->parent_id == $category->id  ? 'selected' : ''}}>{{app()->getLocale() =='en' ? $Category->name_en :  $Category->name_ar }}</option>
                                            @endforeach

                                        </select>
                                         @if($errors->has('parent'))
                                            <div class="alert alert-danger">{{ $errors->first('parent') }}</div>
                                        @endif
                                    </div>

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

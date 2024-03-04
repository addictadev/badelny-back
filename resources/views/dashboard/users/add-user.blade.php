@extends('dashboard.layouts.master')
@section('content')

            <div class="dashboard-content-one">
                <!-- Breadcubs Area Start Here -->
                <div class="breadcrumbs-area">
                    <h3>{{trans('dashboard.Users')}}</h3>
                    <ul>
                        <li>
                            <a href="/">{{trans('dashboard.home')}}</a>
                        </li>
                        <li>{{trans('dashboard.Add_User')}}</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Student Table Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">

                        @if(isset($user))
                            <form class="new-added-form" action="{{route('users.update',$user->id)}}" method="post">
                                @csrf
                                @method('put')
                        @else
                        <form class="new-added-form" action="{{route('users.store')}}" method="post">
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
                                            <input type="text" class="form-control " name="name_en"  value="{{isset($user) ? $user->name_en : ''}}"  placeholder="Name" required>
                                            @if ($errors->has('name_en'))
                                                <p class="text-danger"> {{ $errors->first('name_en') }} </p>
                                            @endif

                                            <label for="area_en">Area <span class="text-danger"></span></label>
                                            <input type="text" class="form-control " name="area_en"  value="{{isset($user) ? $user->area_en : ''}}"  placeholder="Area" >
                                            @if ($errors->has('area_en'))
                                                <p class="text-danger"> {{ $errors->first('area_en') }} </p>
                                            @endif

                                            <label for="description_en">Address</label>
                                            <input type="text" class="form-control " name="address_en"  value="{{isset($user) ? $user->address_en : ''}}"  placeholder="Address" >
                                            @if ($errors->has('address_en'))
                                                <p class="text-danger"> {{ $errors->first('address_en') }} </p>
                                            @endif


                                        </div>

                                        <div class="col-sm-10 tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-home-tab">
                                            <label for="name_ar">الاسم<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control " name="name_ar"  value="{{isset($user) ? $user->name_ar : ''}}"  placeholder="الاسم" required>
                                            @if ($errors->has('name_ar'))
                                                <p class="text-danger"> {{ $errors->first('name_ar') }} </p>
                                            @endif

                                            <label for="area_ar">المنطقة<span class="text-danger"></span></label>
                                            <input type="text" class="form-control " name="area_ar"  value="{{isset($user) ? $user->area_ar : ''}}"  placeholder="المنطقة">
                                            @if ($errors->has('area_ar'))
                                                <p class="text-danger"> {{ $errors->first('area_ar') }} </p>
                                            @endif

                                            <label for="description_en">العنوان</label>
                                            <input type="text" class="form-control " name="address_ar"  value="{{isset($user) ? $user->address_ar : ''}}"  placeholder="العنوان" >
                                            @if ($errors->has('address_ar'))
                                                <p class="text-danger"> {{ $errors->first('address_ar') }} </p>
                                            @endif


                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                <div class="row col-12 form-group">

                                    <label class="col-sm-4">
                                        {{trans('dashboard.Email')}} *
                                    </label>

                                    <div class="col-sm-8">
                                        <input type="email" name="email" required value="{{isset($user) ? $user->email : ''}}" placeholder=" " class="form-control">
                                        @if($errors->has('email'))
                                            <div class="alert alert-danger">{{ $errors->first('email') }}</div>
                                        @endif
                                    </div>

                                </div>
                                <div class="row col-12 form-group">

                                    <label class="col-sm-4">
                                        {{trans('dashboard.phone')}} *
                                    </label>

                                    <div class="col-sm-8">
                                        <input type="number" required name="phone" value="{{isset($user) ? $user->phone : ''}}" placeholder=" " class="form-control">
                                        @if($errors->has('phone'))
                                            <div class="alert alert-danger">{{ $errors->first('phone') }}</div>
                                        @endif
                                    </div>

                                </div>
                                @if(!isset($user))
                                <div class="row col-12 form-group">
                                    <label class="col-sm-4">
                                        {{trans('dashboard.Password')}} *
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="password" name="password" placeholder=" " class="form-control" required>
                                        @if($errors->has('password'))
                                            <div class="alert alert-danger">{{ $errors->first('password') }}</div>
                                        @endif
                                    </div>

                                </div>
                                @endif
                                <div class="row col-12 form-group">

                                    <label class="col-sm-4">
                                        {{trans('dashboard.gender')}}
                                    </label>

                                    <div class="col-sm-8">
                                        <select name="gender" class="form-control">
                                            <option value="m" {{isset($user) && $user->gender = 'm' ? 'selected' : ''}}> {{trans('dashboard.male')}}</option>
                                            <option value="f" {{isset($user) && $user->gender = 'f' ? 'selected' : ''}}> {{trans('dashboard.female')}}</option>
                                        </select>
                                         @if($errors->has('gender'))
                                            <div class="alert alert-danger">{{ $errors->first('gender') }}</div>
                                        @endif
                                    </div>

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

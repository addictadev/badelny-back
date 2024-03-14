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
                                <div class="col-md-10">
                                    <div class="row col-12 form-group">

                                        <label class="col-sm-4">
                                            {{trans('dashboard.Name')}} *
                                        </label>

                                        <div class="col-sm-8">
                                            <input type="text" name="name" required value="{{isset($user) ? $user->name : ''}}" placeholder=" " class="form-control">
                                            @if($errors->has('name'))
                                                <div class="alert alert-danger">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>

                                    </div>
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
                                        {{trans('dashboard.phone')}}
                                    </label>

                                    <div class="col-sm-8">
                                        <input type="number" name="phone" value="{{isset($user) ? $user->phone : ''}}" placeholder=" " class="form-control">
                                        @if($errors->has('phone'))
                                            <div class="alert alert-danger">{{ $errors->first('phone') }}</div>
                                        @endif
                                    </div>

                                </div>
                                    <div class="row col-12 form-group">

                                        <label class="col-sm-4">
                                            {{trans('dashboard.birthDate')}}
                                        </label>

                                        <div class="col-sm-8">
                                            <input type="date" name="date_of_birth" value="{{isset($user) ? $user->date_of_birth : ''}}" placeholder=" " class="form-control">
                                            @if($errors->has('date_of_birth'))
                                                <div class="alert alert-danger">{{ $errors->first('date_of_birth') }}</div>
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
                                            <option value="1" {{isset($user) && $user->gender = 'm' ? 'selected' : ''}}> {{trans('dashboard.male')}}</option>
                                            <option value="2" {{isset($user) && $user->gender = 'f' ? 'selected' : ''}}> {{trans('dashboard.female')}}</option>
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

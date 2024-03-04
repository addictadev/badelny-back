@extends('dashboard.layouts.master')
@section('content')
            <div class="dashboard-content-one" style="height: 1200px">
                <!-- Breadcubs Area Start Here -->
                <div class="breadcrumbs-area">
                    <h3>{{trans('dashboard.Admin Dashboard')}}</h3>
                    <ul>
                        <li>
                            <a href="/">{{trans('dashboard.home')}}</a>
                        </li>
                        <li>{{trans('dashboard.dashboard')}}</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Dashboard summery Start Here -->
                <div class="row gutters-20">
                    <div class="col-xl-3 col-sm-6 col-12">
                        <div class="dashboard-summery-one mg-b-20">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <div class="item-icon bg-light-green ">
                                        <img src="{{asset('users.png')}}" alt="">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="item-content">
                                        <div class="item-title">{{trans('dashboard.Users')}}</div>
                                        <div class="item-number"><span class="counter" data-num="150000">{{$users}}</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12">
                        <div class="dashboard-summery-one mg-b-20">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <div class="item-icon bg-light-blue">

                                        <img src="{{asset('category.png')}}" alt="">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="item-content">
                                        <div class="item-title">{{trans('dashboard.categories')}}</div>
                                        <div class="item-number"><span class="counter" data-num="2250">{{$categories}}</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12">
                        <div class="dashboard-summery-one mg-b-20">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <div class="item-icon bg-light-yellow">
                                        <img src="{{asset('vendors.png')}}" alt="">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="item-content">
                                        <div class="item-title">{{trans('dashboard.Vendors')}}</div>
                                        <div class="item-number"><span class="counter" data-num="5690">0</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12">
                        <div class="dashboard-summery-one mg-b-20">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <div class="item-icon bg-light-red">
                                        <img src="{{asset('offers.png')}}" alt="">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="item-content">
                                        <div class="item-title">{{trans('dashboard.Order')}}</div>
                                        <div class="item-number"><span class="counter" data-num="193000">0</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Dashboard summery End Here -->

@endsection

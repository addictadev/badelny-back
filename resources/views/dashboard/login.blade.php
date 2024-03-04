<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{trans('dashboard.badelny_login')}}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.png">
    <!-- Normalize CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/normalize.css')}}">
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/main.css')}}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/all.min.css')}}">
    <!-- Flaticon CSS -->
    <link rel="stylesheet" href="{{asset('assets/fonts/flaticon.css')}}">
    <!-- Animate CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/animate.min.css')}}">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{asset('assets/style.css')}}">
    <!-- Modernize js -->
    <script src="{{asset('assets/js/modernizr-3.6.0.min.js')}}"></script>
</head>

<body>
    <!-- Preloader Start Here -->
    <div id="preloader"></div>
    <!-- Preloader End Here -->
    <!-- Login Page Start Here -->
    <div class="login-page-wrap">
        <div class="login-page-content">
            <div class="login-box">
{{--                <div class="item-logo">--}}
{{--                    {{trans('dashboard.Login')}} <img src="{{asset('assets/img/logo_edit.png')}}" height="60px" width="60px">--}}
{{--                </div>--}}
                <form method="POST" class="login-form" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label>{{trans('dashboard.Email')}}</label>
                        <input type="text" name="email" placeholder="Enter Email" class="form-control">
                        <i class="far fa-envelope"></i>
                        @if($errors->has('email'))
                            <div class="alert alert-danger">{{ $errors->first('email') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>{{trans('dashboard.Password')}}</label>
                        <input type="text" name="password" placeholder="Enter password" class="form-control">
                        <i class="fas fa-lock"></i>
                        @if($errors->has('password'))
                            <div class="alert alert-danger">{{ $errors->first('password') }}</div>
                        @endif
                    </div>
                    <div class="form-group d-flex align-items-center justify-content-between">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember-me">
                            <label for="remember-me" class="form-check-label">{{trans('dashboard.Remember Me')}}</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="login-btn">{{trans('dashboard.Login')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Login Page End Here -->
    <!-- jquery-->
    <script src="{{asset('assets/js/jquery-3.3.1.min.js')}}"></script>
    <!-- Plugins js -->
    <script src="{{asset('assets/js/plugins.js')}}"></script>
    <!-- Popper js -->
    <script src="{{asset('assets/js/popper.min.js')}}"></script>
    <!-- Bootstrap js -->
    <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
    <!-- Scroll Up Js -->
    <script src="{{asset('assets/js/jquery.scrollUp.min.js')}}"></script>
    <!-- Custom Js -->
    <script src="{{asset('assets/js/main.js')}}"></script>

</body>

</html>

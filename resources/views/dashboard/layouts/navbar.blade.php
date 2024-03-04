<div class="navbar navbar-expand-md header-menu-one bg-light">
    <div class="nav-bar-header-one">
        <div class="toggle-button sidebar-toggle">
            <button type="button" class="item-link">
                        <span class="btn-icon-wrap">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
            </button>
        </div>
    </div>
    <div class="d-md-none mobile-nav-bar">
        <button class="navbar-toggler pulse-animation" type="button" data-toggle="collapse" data-target="#mobile-navbar" aria-expanded="false">
            <i class="far fa-arrow-alt-circle-down"></i>
        </button>
        <button type="button" class="navbar-toggler sidebar-toggle-mobile">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    <div class="header-main-menu collapse navbar-collapse" id="mobile-navbar">
        <ul class="navbar-nav">
            <li class="navbar-item header-search-bar">

            </li>
        </ul>
        <ul class="navbar-nav">
            <li class="navbar-item dropdown header-admin">
                <a class="navbar-nav-link " href="#" role="button" data-toggle="dropdown"
                   aria-expanded="false">
                    <div class="admin-title">
                        <h5 class="item-title">
                            @if(auth()->check())
                                {{app()->getLocale() =='en' ? auth()->user()->name_en : auth()->user()->name_ar}}
                            @endif
                        </h5>
                        <span>{{trans('dashboard.admin')}}</span>
                    </div>
                    <div class="dropdown-menu dropdown-menu-right">

                        <a class="dropdown-item" href="{{ route('dashboard.logout') }}">{{trans('auth.logout')}}</a>
                    </div>
                </a>
            </li>
            <li class="navbar-item dropdown header-message">

                <img src="{{asset('assets/img/house.png')}}" height="30px" width="30px">
                <a href="{{route('dashboard.home')}}" style="color: #0C1021">{{trans('dashboard.home')}}</a>
            </li>
            <li class="navbar-item dropdown header-language">
                <a class="navbar-nav-link " href="#" role="button"
                   data-toggle="dropdown" aria-expanded="false"><i class="fas fa-globe-americas"></i>| {{app()->getLocale() =='en' ? 'en' : 'ع'}}</a>
                <div class="dropdown-menu ">
                    @if(app()->getLocale() == 'ar')
                        <a class="dropdown-item" href="{{ LaravelLocalization::getLocalizedURL('en', null, [], true) }}">English</a>
                    @else
                        <a class="dropdown-item" href="{{ LaravelLocalization::getLocalizedURL('ar', null, [], true) }}">عربى</a>
                    @endif
                </div>
            </li>
        </ul>
    </div>

</div>



<div class="sidebar-main sidebar-menu-one sidebar-expand-md sidebar-color" style="height: 1000px" >
    <div class="mobile-sidebar-header d-md-none">
        <div class="header-logo">
            <a href="/"><img src="{{asset('assets/img/logo_edit.png')}}" width="60" alt="logo"></a>
        </div>
    </div>
    <div class="sidebar-menu-content" >
        <ul class="nav nav-sidebar-menu sidebar-toggle-view">
        <li class="nav-item sidebar-nav-item">
            <a href="#" class="nav-link"><img src="{{asset('users.png')}}" alt="" width="20px" height="30px" style="margin: 0 10px 0 10px"><span>{{trans('dashboard.Users')}}</span></a>
            <ul class="nav sub-group-menu @if(request()->path() == 'users' || request()->path() == 'ar/users' || request()->path() == 'users/create' || request()->path() == 'ar/users/create' )sub-group-active" @endif">

        <li class="nav-item">
            <a href="{{route('users.create')}}" class="nav-link @if(request()->path() == 'users/create' ||  request()->path() == 'ar/users/create')menu-active" @endif
            ><i class="fas fa-angle-right"></i>{{trans('dashboard.add')}}</a>
        </li>
        <li class="nav-item">
            <a href="{{route('users.index')}}" class="nav-link @if(request()->path() == 'users' || request()->path() == 'ar/users')menu-active" @endif><i
                    class="fas fa-angle-right"></i>{{trans('dashboard.All Users')}}</a>
        </li>
        </ul>

            <li class="nav-item sidebar-nav-item">
                <a href="#" class="nav-link"><img src="{{asset('category.png')}}" alt="" width="20px" height="30px" style="margin: 0 10px 0 10px"><span>{{trans('dashboard.categories')}}</span></a>
                <ul class="nav sub-group-menu @if(request()->path() == 'categories' || request()->path() == 'ar/categories' || request()->path() == 'categories/create' || request()->path() == 'ar/categories/create' )sub-group-active" @endif">

            <li class="nav-item">
                <a href="{{route('categories.create')}}" class="nav-link @if(request()->path() == 'categories/create' ||  request()->path() == 'ar/categories/create')menu-active" @endif
                ><i class="fas fa-angle-right"></i>{{trans('dashboard.add')}}</a>
            </li>
            <li class="nav-item">
                <a href="{{route('categories.index')}}" class="nav-link @if(request()->path() == 'categories' || request()->path() == 'ar/categories')menu-active" @endif><i
                        class="fas fa-angle-right"></i>{{trans('dashboard.All_Categories')}}</a>
            </li>
        </ul>

    </div>
</div>

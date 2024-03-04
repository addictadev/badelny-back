@include('dashboard.layouts.head')

<div>
<!-- Preloader Start Here -->
<div id="preloader"></div>

<!-- Preloader End Here -->
<div id="wrapper" class="wrapper bg-ash">
    <!-- Header Menu Area Start Here -->
@include('dashboard.layouts.navbar')
<!-- Header Menu Area End Here -->
    <!-- Page Area Start Here -->

    <div class="dashboard-page-one" >

        <!-- Sidebar Area Start Here -->
    @include('dashboard.layouts.sidebar')

    <!-- Sidebar Area End Here -->
        @yield('content')

        @include('dashboard.layouts.footer')
    </div>
<!-- Page Area End Here -->
</div>
</div>

@include('dashboard.layouts.script')
    @yield('script')
</body>

</html>

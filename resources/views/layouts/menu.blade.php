
<li class="nav-item">
    <a href="{{ route('tests.index') }}" class="nav-link {{ Request::is('tests*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Tests</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('users.index') }}" class="nav-link {{ Request::is('users*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Users</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('products.index') }}" class="nav-link {{ Request::is('products*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Products</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('orders.index') }}" class="nav-link {{ Request::is('orders*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Orders</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('areas.index') }}" class="nav-link {{ Request::is('areas*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Areas</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('usersAddresses.index') }}" class="nav-link {{ Request::is('usersAddresses*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Users Addresses</p>
    </a>
</li>

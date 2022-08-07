<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
        <img src="{{ asset('asset') }}/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
        <a href="{{ route('users.index') }}" class="d-block">{{ isset(auth()->user()->name) ? auth()->user()->name : 'Users' }}</a>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->
        <li class="nav-item menu-open">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
                Dashboard
            </p>
            </a>
        </li>
        @can('member-module')
        <li class="nav-item">
            <a href="{{ route('members.index') }}" class="nav-link {{ request()->routeIs('members.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users"></i>
            <p>
                Pengurus
            </p>
            </a>
        </li>
        @endcan
        @can('profile-module')
        <li class="nav-item">
            <a href="{{ route('profiles.index') }}" class="nav-link {{ request()->routeIs('profiles.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-cog"></i>
            <p>
                Profil
            </p>
            </a>
        </li>
        @endcan
        <li class="nav-item">
            @can('transaction-module')
            <a href="{{ route('transactions.index') }}" class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-money-bill"></i>
            <p>
                Keuangan
            </p>
            </a>
            @endcan
        </li>
        <li class="nav-item">
            @can('transaction-module')
            <a href="{{ route('trash.transactions') }}" class="nav-link {{ request()->routeIs('trash.transactions') ? 'active' : '' }}">
            <i class="nav-icon fas fa-trash-alt"></i>
            <p>
                Data Sampah
            </p>
            </a>
            @endcan
        </li>
        <li class="nav-item">
            @can('user-list')
            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') || request()->routeIs('roles.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-user"></i>
            <p>
                Pengguna
                <i class="right fas fa-angle-left"></i>
            </p>
            </a>
            @endcan
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    @can('user-list')
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        Data Pengguna
                    </p>
                    </a>
                    @endcan
                </li>
                <li class="nav-item">
                    @can('role-list')
                    <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user-tag"></i>
                    <p>
                        Role Pengguna
                    </p>
                    </a>
                    @endcan
                </li>
            </ul>
        </li>
        @guest
        @else
        <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}"
                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
            <i class="nav-icon fas fa-sign-out-alt" style="color: rgb(184, 0, 0);"></i>
            <p>
                <span>Keluar</span>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </p>
            </a>
        </li>
        @endguest

        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>

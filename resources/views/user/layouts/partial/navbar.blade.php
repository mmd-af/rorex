<nav class="sb-topnav navbar navbar-expand navbar-dark" style="background-color: #009799;">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="{{url('/')}}">rorex.ro</a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0 mx-3" id="sidebarToggle" href="#!"><i
            class="fas fa-bars"></i></button>
    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..."
                   aria-describedby="btnNavbarSearch"/>
            <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
        </div>
    </form>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
               aria-expanded="false">{{ Auth::user()->name }} ({{Auth::user()->cod_staff}})<i
                    class="fas fa-user fa-fw"></i></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="{{route('profile.edit')}}">Profile</a></li>
                @if (!empty(auth()->user()->getRoleNames()->toArray())|| Auth::user()->rolles == 'admin')
                    <li><a class="dropdown-item" href="{{route('admin.dashboard.index')}}">Admin Panel</a></li>
                @endif
                <li>
                    <hr class="dropdown-divider"/>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button class="btn" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>

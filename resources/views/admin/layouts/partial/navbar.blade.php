<nav class="sb-topnav navbar navbar-expand navbar-dark px-3" style="background-color: #212529">
    <button class="btn btn-link btn-sm bg-white text-dark" id="sidebarToggle" href="#!">
        <i class="fas fa-bars"></i>
    </button>
    <a class="navbar-brand ps-3" href="{{url('/')}}">rorex.ro</a>
    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..."
                   aria-describedby="btnNavbarSearch"/>
            <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i>
            </button>
        </div>
    </form>
    <button type="button" class="btn position-relative mx-3 mt-3">
        <i class="fa-regular fa-envelope text-white"></i>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            +3
                        <span class="visually-hidden">unread messages</span>
                     </span>
    </button>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
               aria-expanded="false">{{ Auth::user()->name }} ({{Auth::user()->cod_staff}})<i
                    class="fas fa-user fa-fw"></i></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="{{route('user.dashboard.index')}}">User Panel</a></li>
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

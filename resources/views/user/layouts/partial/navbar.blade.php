<nav class="sb-topnav navbar navbar-expand navbar-dark px-3" style="background-color: #009799;">
    <button class="btn btn-link btn-sm bg-white text-dark" id="sidebarToggle" href="#!">
        <i class="fas fa-bars"></i>
    </button>
    <a class="navbar-brand ps-3" href="{{ url('/') }}">{{ __('layouts.rorex_ro') }}</a>

    @php
        $currentLanguage = session('locale', 'en');
        $flags = [
            'en' => '🇬🇧',
            'ro' => '🇷🇴',
            'fa' => '🇮🇷',
        ];
    @endphp

    <div class="dropdown ms-auto mx-1 rounded-3 p-2 shadow">
        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdownMenuLink"
            role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="me-2">{{ $flags[$currentLanguage] ?? '🇬🇧' }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
            <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">🇬🇧 English</a></li>
            <li><a class="dropdown-item" href="{{ route('lang.switch', 'ro') }}">🇷🇴 Romanian</a></li>
            <li><a class="dropdown-item" href="{{ route('lang.switch', 'fa') }}">🇮🇷 Persian</a></li>
        </ul>
    </div>

    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                {{ Auth::user()->employee?->last_name }}
                ({{ Auth::user()->employee?->staff_code }})<i class="fas fa-user fa-fw"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                @if (!empty(auth()->user()->getRoleNames()->toArray()) || Auth::user()->rolles == 'admin')
                    <li><a class="dropdown-item"
                            href="{{ route('admin.dashboard.index') }}">{{ __('layouts.admin_panel') }}</a></li>
                @endif
                <li>
                    <hr class="dropdown-divider" />
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn" onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>

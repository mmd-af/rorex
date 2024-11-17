<nav class="sb-topnav navbar navbar-expand navbar-dark px-3" style="background-color: #009799;">
    <button class="btn btn-link btn-sm bg-white text-dark" id="sidebarToggle" href="#!">
        <i class="fas fa-bars"></i>
    </button>
    <a class="navbar-brand ps-3" href="{{ url('/') }}">{{ __('layouts.rorex_ro') }}</a>

    @php
        $currentLanguage = session('locale', 'en');
        $languages = [
            'en' => ['flag' => 'https://upload.wikimedia.org/wikipedia/commons/a/a4/Flag_of_the_United_States.svg', 'name' => 'English'],
            'ro' => ['flag' => 'https://upload.wikimedia.org/wikipedia/commons/7/73/Flag_of_Romania.svg', 'name' => 'Romanian'],
            'fa' => ['flag' => 'https://upload.wikimedia.org/wikipedia/commons/c/ca/Flag_of_Iran.svg', 'name' => 'فارسی'],
        ];
    @endphp

    <div class="dropdown ms-auto mx-1 rounded-3 p-2 shadow">
        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdownMenuLink"
           role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{ $languages[$currentLanguage]['flag'] ?? $languages['en']['flag'] }}"
                 alt="{{ $languages[$currentLanguage]['name'] ?? 'English' }}"
                 style="width: 20px; height: 15px;" class="me-2">
            <span>{{ $languages[$currentLanguage]['name'] ?? 'English' }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
            @foreach($languages as $code => $lang)
                <li>
                    <a class="dropdown-item d-flex align-items-center" href="{{ route('lang.switch', $code) }}">
                        <img src="{{ $lang['flag'] }}" alt="{{ $lang['name'] }}"
                             style="width: 20px; height: 15px;" class="me-2">
                        {{ $lang['name'] }}
                    </a>
                </li>
            @endforeach
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
                    <hr class="dropdown-divider"/>
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

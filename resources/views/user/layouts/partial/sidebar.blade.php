<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <div class="sb-sidenav-menu-heading">{{ __('layouts.core') }}</div>
            <a class="nav-link" href="{{ route('user.dashboard.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                {{ __('layouts.dashboard') }}
            </a>
            <div class="sb-sidenav-menu-heading">{{ __('layouts.reports') }}</div>
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts"
                aria-expanded="false" aria-controls="collapseLayouts">
                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                {{ __('layouts.reports') }}
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="{{ route('user.dailyReports.index') }}">{{ __('layouts.daily') }}</a>
                    <a class="nav-link" href="{{ route('user.monthlyReports.index') }}">{{ __('layouts.monthly') }}</a>
                </nav>
            </div>

            <div class="sb-sidenav-menu-heading">{{ __('layouts.requests') }}</div>
            <a class="nav-link" href="{{ route('user.leaves.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                {{ __('layouts.leave_requests') }}
            </a>
            <a class="nav-link" href="{{ route('user.staffRequests.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                {{ __('layouts.other_requests') }}
            </a>
            {{-- <a class="nav-link" href="{{route('user.supports.index')}}">
                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                Support
            </a> --}}
        </div>
    </div>
    <div class="sb-sidenav-footer">
        <div class="small">{{ __('layouts.logged_in_as') }}</div>
        {{ Auth::user()->name }}
    </div>
</nav>

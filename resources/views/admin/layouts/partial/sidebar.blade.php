<nav class="sb-sidenav accordion sb-sidenav-admin" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <div class="sb-sidenav-menu-heading">Core</div>
            <a class="nav-link" href="{{ route('admin.dashboard.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
            </a>
            @canany(['daily_reports', 'monthly_reports', 'manage_leaves'])
                <div class="sb-sidenav-menu-heading">Reports</div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts"
                    aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Reports
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        @can('daily_reports')
                            <a class="nav-link" href="{{ route('admin.dailyReports.index') }}">Daily</a>
                        @endcan
                        @can('monthly_reports')
                            <a class="nav-link" href="{{ route('admin.monthlyReports.index') }}">Monthly</a>
                        @endcan
                        @can('manage_leaves')
                            <a class="nav-link" href="{{ route('admin.manageStaffLeaves.index') }}">Leave Control</a>
                        @endcan
                        @can('daily_reports')
                            <a class="nav-link" href="{{ route('admin.dailyReports.singleReports.index') }}">Single records</a>
                        @endcan
                    </nav>
                </div>
            @endcanany
            <div class="sb-sidenav-menu-heading">Requests</div>
            <a class="nav-link" href="{{ route('admin.manageRequests.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                Manage Requests
            </a>
            <a class="nav-link" href="{{ route('admin.supports.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                Support
            </a>
            @if (Auth::user()->rolles == 'admin')
                <div class="sb-sidenav-menu-heading">Department</div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                    data-bs-target="#DepartmentCollapseLayouts" aria-expanded="false"
                    aria-controls="DepartmentCollapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Members
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="DepartmentCollapseLayouts" aria-labelledby="headingOne"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('admin.users.index') }}">Users</a>
                        <a class="nav-link" href="{{ route('admin.permissions.index') }}">Permission</a>
                        <a class="nav-link" href="{{ route('admin.roles.index') }}">Roles</a>
                    </nav>
                </div>
            @endif
            @canany(['transportations_control', 'companies_control', 'trucks_control'])
                <div class="sb-sidenav-menu-heading">Transportations</div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                    data-bs-target="#TransportationsCollapseLayouts" aria-expanded="false"
                    aria-controls="TransportationsCollapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Transportation
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="TransportationsCollapseLayouts" aria-labelledby="headingOne"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        @can('transportations_control')
                            <a class="nav-link" href="{{ route('admin.transportations.index') }}">Transportation</a>
                        @endcan
                        @can('orders_control')
                            <a class="nav-link" href="{{ route('admin.orders.index') }}">Order</a>
                        @endcan
                        @can('companies_control')
                            <a class="nav-link" href="{{ route('admin.companies.index') }}">Companies</a>
                        @endcan
                        @can('trucks_control')
                            <a class="nav-link" href="{{ route('admin.trucks.index') }}">Truck Definition</a>
                        @endcan
                    </nav>
                </div>
            @endcanany
        </div>
    </div>
    <div class="sb-sidenav-footer">
        <div class="small">Logged in as:</div>
        {{ Auth::user()->name }}
    </div>
</nav>

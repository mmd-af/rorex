<nav class="sb-sidenav accordion sb-sidenav-admin" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <div class="sb-sidenav-menu-heading">Core</div>
            <a class="nav-link" href="{{route('admin.dashboard.index')}}">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
            </a>
            <div class="sb-sidenav-menu-heading">Reports</div>
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts"
               aria-expanded="false" aria-controls="collapseLayouts">
                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                Reports
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne"
                 data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="{{route('admin.dailyReports.index')}}">Daily</a>
                    <a class="nav-link" href="{{route('admin.monthlyReports.index')}}">Monthly</a>
                    <a class="nav-link" href="{{route('admin.manageStaffLeaves.index')}}">Leave Control</a>
                    <a class="nav-link" href="{{route('admin.dailyReports.singleReports.index')}}">Single records</a>
                </nav>
            </div>
            <div class="sb-sidenav-menu-heading">Requests</div>
            <a class="nav-link" href="{{route('admin.manageRequests.index')}}">
                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                Manage Requests
            </a>
            <a class="nav-link" href="{{route('admin.supports.index')}}">
                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                Support
            </a>
            <div class="sb-sidenav-menu-heading">Department</div>
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#DepartmentCollapseLayouts"
               aria-expanded="false" aria-controls="DepartmentCollapseLayouts">
                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                Members
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="DepartmentCollapseLayouts" aria-labelledby="headingOne"
                 data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="{{route('admin.users.index')}}">Users</a>
                    <a class="nav-link" href="{{route('admin.companies.index')}}">Companies</a>
                    <a class="nav-link" href="{{route('admin.permissions.index')}}">Permission</a>
                    <a class="nav-link" href="{{route('admin.roles.index')}}">Roles</a>
                </nav>
            </div>
        </div>
    </div>
    <div class="sb-sidenav-footer">
        <div class="small">Logged in as:</div>
        {{ Auth::user()->name }}
    </div>
</nav>

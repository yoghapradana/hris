<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Employee Self Service</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ Request::is('dashboard') ? 'active' : '' }}">
        <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ url('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        User Menu
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item {{ (Request::is('attendance')||Request::is('timesheets')) ? 'active' : '' }}">
        <a class="nav-link {{ (Request::is('attendance')||Request::is('timesheets')) ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseRecords"
            aria-expanded="true" aria-controls="collapseRecords">
            <i class="fas fa-fw fa-history"></i>
            <span>Records</span>
        </a>
        <div id="collapseRecords" class="collapse {{ (Request::is('attendance')||Request::is('timesheets')) ? 'show' : '' }}" aria-labelledby="headingRecords" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ Request::is('attendance') ? 'active' : '' }}" href="{{ route('attendance') }}">Attendance</a>
                <a class="collapse-item {{ Request::is('timesheets') ? 'active' : '' }}" href="{{ route('timesheets.index') }}">Timesheet</a>
            </div>
        </div>
    </li>


    @if (auth()->user() && in_array(auth()->user()->user_level, ['admin', 'manager']))
        <!-- Divider -->
        <hr class="sidebar-divider">
        <div class="sidebar-heading">
            Manager Menu
        </div>
        <li class="nav-item {{ (Route::is('attendance.pending')||Route::is('profile.index')) ? 'active' : '' }}">
            <a class="nav-link {{ (Route::is('attendance.pending')||Route::is('profile.index')) ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseEmployee"
                aria-expanded="true" aria-controls="collapseEmployee">
                <i class="fas fa-fw fa-history"></i>
                <span>Employee</span>
            </a>
            <div id="collapseEmployee" class="collapse {{ (Route::is('attendance.pending')||Route::is('profile.index')) ? 'show' : '' }}" aria-labelledby="headingEmployee" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item {{ Route::is('attendance.pending') ? 'active' : '' }}" href="{{ route('attendance.pending') }}">Attendance List</a>
                    <a class="collapse-item {{ Route::is('profile.index') ? 'active' : '' }}" href="{{ route('profile.index') }}">Employee list</a>
                </div>
            </div>
        </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
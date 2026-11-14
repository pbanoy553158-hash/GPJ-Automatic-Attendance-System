
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title', 'Dashboard') | Attendance System
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="app-body">

    <div class="app-shell">

        <!-- ========================================= -->
        <!-- SIDEBAR -->
        <!-- ========================================= -->

        <aside class="sidebar" id="sidebar">

            <!-- Brand -->

            <div class="sidebar-brand">

                <div class="sidebar-brand-icon">
                    <i class="bi bi-fingerprint"></i>
                </div>

                <div>
                    <h5>Attendance</h5>
                    <span>Monitoring System</span>
                </div>

            </div>

            <div class="sidebar-divider"></div>

            <!-- Main Navigation -->

            <div class="sidebar-section-label">
                MAIN MENU
            </div>

            <nav class="sidebar-nav">

                <!-- Dashboard -->

                <a
                    href="{{ route('dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                >
                    <i class="bi bi-grid-1x2"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Student Management -->

                <a
                    href="{{ route('students.index') }}"
                    class="sidebar-link {{ request()->routeIs('students.*') ? 'active' : '' }}"
                >
                    <i class="bi bi-people"></i>
                    <span>Student Management</span>
                </a>

                <!-- Guardian Management -->

                <a
                    href="{{ route('guardians.index') }}"
                    class="sidebar-link {{ request()->routeIs('guardians.*') ? 'active' : '' }}"
                >
                    <i class="bi bi-person-hearts"></i>
                    <span>Guardian Management</span>
                </a>

                <!-- RFID Management -->

                <a
                    href="{{ route('rfid.index') }}"
                    class="sidebar-link {{ request()->routeIs('rfid.*') ? 'active' : '' }}"
                >
                    <i class="bi bi-credit-card"></i>
                    <span>RFID Management</span>
                </a>

                <!-- Fingerprint Registration -->

                <a
                    href="{{ route('fingerprints.index') }}"
                    class="sidebar-link {{ request()->routeIs('fingerprints.*') ? 'active' : '' }}"
                >
                    <i class="bi bi-fingerprint"></i>
                    <span>Fingerprint Registration</span>
                </a>

                <!-- ========================================= -->
                <!-- ATTENDANCE -->
                <!-- ========================================= -->

                <div class="sidebar-section-label mt-4">
                    ATTENDANCE
                </div>

                <!-- Attendance Monitoring -->

                <a
                    href="{{ route('modules.placeholder', ['module' => 'monitoring']) }}"
                    class="sidebar-link {{ request()->is('modules/monitoring') ? 'active' : '' }}"
                >
                    <i class="bi bi-display"></i>
                    <span>Attendance Monitoring</span>
                </a>

                <!-- Attendance Records -->

                <a
                    href="{{ route('modules.placeholder', ['module' => 'records']) }}"
                    class="sidebar-link {{ request()->is('modules/records') ? 'active' : '' }}"
                >
                    <i class="bi bi-calendar2-check"></i>
                    <span>Attendance Records</span>
                </a>

                <!-- Schedule Management -->

                <a
                    href="{{ route('modules.placeholder', ['module' => 'schedules']) }}"
                    class="sidebar-link {{ request()->is('modules/schedules') ? 'active' : '' }}"
                >
                    <i class="bi bi-calendar-week"></i>
                    <span>Schedule Management</span>
                </a>

                <!-- ========================================= -->
                <!-- SYSTEM -->
                <!-- ========================================= -->

                <div class="sidebar-section-label mt-4">
                    SYSTEM
                </div>

                <!-- Reports -->

                <a
                    href="{{ route('modules.placeholder', ['module' => 'reports']) }}"
                    class="sidebar-link {{ request()->is('modules/reports') ? 'active' : '' }}"
                >
                    <i class="bi bi-bar-chart"></i>
                    <span>Reports and Analytics</span>
                </a>

                <!-- SMS Notifications -->

                <a
                    href="{{ route('modules.placeholder', ['module' => 'sms']) }}"
                    class="sidebar-link {{ request()->is('modules/sms') ? 'active' : '' }}"
                >
                    <i class="bi bi-chat-dots"></i>
                    <span>SMS Notifications</span>
                </a>

                <!-- Device Management -->

                <a
                    href="{{ route('modules.placeholder', ['module' => 'devices']) }}"
                    class="sidebar-link {{ request()->is('modules/devices') ? 'active' : '' }}"
                >
                    <i class="bi bi-cpu"></i>
                    <span>Device Management</span>
                </a>

                <!-- ========================================= -->
                <!-- ADMINISTRATION -->
                <!-- ========================================= -->

                @if(auth()->user()->isAdmin())

                    <div class="sidebar-section-label mt-4">
                        ADMINISTRATION
                    </div>

                    <!-- User Management -->

                    <a
                        href="{{ route('modules.placeholder', ['module' => 'users']) }}"
                        class="sidebar-link {{ request()->is('modules/users') ? 'active' : '' }}"
                    >
                        <i class="bi bi-person-gear"></i>
                        <span>User Management</span>
                    </a>

                    <!-- System Settings -->

                    <a
                        href="{{ route('modules.placeholder', ['module' => 'settings']) }}"
                        class="sidebar-link {{ request()->is('modules/settings') ? 'active' : '' }}"
                    >
                        <i class="bi bi-gear"></i>
                        <span>System Settings</span>
                    </a>

                    <!-- Audit Logs -->

                    <a
                        href="{{ route('modules.placeholder', ['module' => 'audit-logs']) }}"
                        class="sidebar-link {{ request()->is('modules/audit-logs') ? 'active' : '' }}"
                    >
                        <i class="bi bi-journal-text"></i>
                        <span>Audit Logs</span>
                    </a>

                @endif

            </nav>

            <!-- ========================================= -->
            <!-- SIDEBAR BOTTOM -->
            <!-- ========================================= -->

            <div class="sidebar-bottom">

                <div class="sidebar-user">

                    <div class="sidebar-user-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <div class="sidebar-user-info">

                        <strong>
                            {{ auth()->user()->name }}
                        </strong>

                        <span>
                            {{ ucfirst(auth()->user()->role) }}
                        </span>

                    </div>

                </div>

                <!-- Logout -->

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        class="sidebar-logout"
                    >
                        <i class="bi bi-box-arrow-left"></i>
                        Logout
                    </button>

                </form>

            </div>

        </aside>

        <!-- Sidebar Backdrop -->

        <div
            class="sidebar-backdrop"
            id="sidebarBackdrop"
        ></div>

        <!-- ========================================= -->
        <!-- MAIN CONTENT -->
        <!-- ========================================= -->

        <main class="main-content">

            <!-- Topbar -->

            <header class="topbar">

                <!-- Mobile Sidebar Toggle -->

                <button
                    type="button"
                    class="sidebar-toggle"
                    id="sidebarToggle"
                    aria-label="Toggle navigation"
                >
                    <i class="bi bi-list"></i>
                </button>

                <!-- Page Title -->

                <div class="topbar-title">

                    <h6>
                        @yield('page-heading', 'Dashboard')
                    </h6>

                    <span>
                        Automatic Student Attendance System
                    </span>

                </div>

                <!-- Topbar Right -->

                <div class="topbar-right">

                    <div class="topbar-date">

                        <i class="bi bi-calendar3"></i>

                        {{ now()->format('F d, Y') }}

                    </div>

                    <div class="topbar-avatar">

                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                    </div>

                </div>

            </header>

            <!-- ========================================= -->
            <!-- PAGE CONTENT -->
            <!-- ========================================= -->

            <div class="page-content">

                <!-- Success Message -->

                @if(session('success'))

                    <div class="alert alert-success alert-dismissible fade show">

                        <i class="bi bi-check-circle me-2"></i>

                        {{ session('success') }}

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close"
                        ></button>

                    </div>

                @endif

                <!-- Error Message -->

                @if(session('error'))

                    <div class="alert alert-danger alert-dismissible fade show">

                        <i class="bi bi-exclamation-circle me-2"></i>

                        {{ session('error') }}

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close"
                        ></button>

                    </div>

                @endif

                <!-- Validation Errors -->

                @if($errors->any())

                    <div class="alert alert-danger alert-dismissible fade show">

                        <strong>Please correct the following errors:</strong>

                        <ul class="mb-0 mt-2">

                            @foreach($errors->all() as $error)

                                <li>{{ $error }}</li>

                            @endforeach

                        </ul>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close"
                        ></button>

                    </div>

                @endif

                <!-- Dynamic Page Content -->

                @yield('content')

            </div>

            <!-- ========================================= -->
            <!-- FOOTER -->
            <!-- ========================================= -->

            <footer class="app-footer">

                <span>
                    Automatic Student Attendance Monitoring System
                </span>

                <span>
                    © {{ date('Y') }}
                </span>

            </footer>

        </main>

    </div>

</body>
</html>

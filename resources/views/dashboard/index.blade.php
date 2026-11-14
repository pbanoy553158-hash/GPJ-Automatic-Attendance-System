@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-heading', 'Dashboard Overview')

@section('content')

    <div class="dashboard-heading">
        <div>
            <h2>Dashboard Overview</h2>
            <p>Welcome back, {{ auth()->user()->name }}!</p>
        </div>

        <div class="dashboard-date">
            <i class="bi bi-calendar-event"></i>
            {{ now()->format('l, F d, Y') }}
        </div>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="row g-3 mb-4">

        @foreach($cards as $card)

            <div class="col-12 col-sm-6 col-xl-3">

                <div class="stat-card">

                    <div class="stat-card-top">
                        <span class="stat-label">
                            {{ $card['label'] }}
                        </span>

                        <div class="stat-icon {{ $card['color'] }}">
                            <i class="bi {{ $card['icon'] }}"></i>
                        </div>
                    </div>

                    <div class="stat-value">
                        {{ $card['value'] }}
                    </div>

                    <div class="stat-footer">
                        <span>Current database records</span>
                    </div>

                </div>

            </div>

        @endforeach

    </div>

    {{-- CHARTS --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-xl-8">

            <div class="content-card h-100">

                <div class="card-heading">
                    <div>
                        <h5>Attendance Trends</h5>
                        <p>Present, late, and absent students</p>
                    </div>

                    <span class="chart-period">Last 7 Days</span>
                </div>

                <div class="chart-container">
                    <canvas id="attendanceChart"></canvas>
                </div>

            </div>

        </div>

        <div class="col-12 col-xl-4">

            <div class="content-card h-100">

                <div class="card-heading">
                    <div>
                        <h5>Today's Attendance</h5>
                        <p>Attendance status distribution</p>
                    </div>
                </div>

                <div class="chart-container chart-container-small">
                    <canvas id="statusChart"></canvas>
                </div>

            </div>

        </div>

        <div class="col-12 col-xl-6">

            <div class="content-card h-100">

                <div class="card-heading">
                    <div>
                        <h5>Attendance by Course</h5>
                        <p>Today's recorded attendance</p>
                    </div>
                </div>

                <div class="chart-container">
                    <canvas id="courseChart"></canvas>
                </div>

            </div>

        </div>

        <div class="col-12 col-xl-6">

            <div class="content-card h-100">

                <div class="card-heading">
                    <div>
                        <h5>SMS Activity</h5>
                        <p>Notification status over the last 7 days</p>
                    </div>
                </div>

                <div class="chart-container">
                    <canvas id="smsChart"></canvas>
                </div>

            </div>

        </div>

    </div>

    {{-- RECENT ATTENDANCE --}}
    <div class="content-card mb-4">

        <div class="card-heading">
            <div>
                <h5>Recent Attendance</h5>
                <p>Latest attendance records for today</p>
            </div>

            <a
                href="{{ route('modules.placeholder', ['module' => 'records']) }}"
                class="view-all-link"
            >
                View All <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="table-responsive">

            <table class="table dashboard-table align-middle">

                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Student ID</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($recentAttendance as $record)

                        <tr>
                            <td>
                                <div class="table-person">
                                    <div class="table-avatar">
                                        {{ strtoupper(substr($record->student?->first_name ?? '?', 0, 1)) }}
                                    </div>

                                    <div>
                                        <strong>
                                            {{ $record->student?->full_name ?? 'Deleted Student' }}
                                        </strong>

                                        <small>
                                            {{ $record->student?->course ?? 'No course assigned' }}
                                        </small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                {{ $record->student?->student_id ?? '—' }}
                            </td>

                            <td>
                                {{ $record->time_in?->format('h:i A') ?? '—' }}
                            </td>

                            <td>
                                {{ $record->time_out?->format('h:i A') ?? '—' }}
                            </td>

                            <td>
                                <span class="status-badge status-{{ strtolower($record->status) }}">
                                    {{ ucfirst($record->status) }}
                                </span>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="empty-state">
                                <i class="bi bi-calendar-x"></i>
                                <span>No attendance records for today.</span>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- LOWER TABLES --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-xl-6">

            <div class="content-card h-100">

                <div class="card-heading">
                    <div>
                        <h5>Currently Present</h5>
                        <p>Students who have not recorded Time Out</p>
                    </div>
                </div>

                <div class="table-responsive">

                    <table class="table dashboard-table align-middle">

                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Time In</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($currentlyPresentStudents as $record)

                                <tr>
                                    <td>
                                        <strong>
                                            {{ $record->student?->full_name ?? 'Deleted Student' }}
                                        </strong>

                                        <small class="d-block text-muted">
                                            {{ $record->student?->student_id ?? '—' }}
                                        </small>
                                    </td>

                                    <td>
                                        {{ $record->time_in?->format('h:i A') ?? '—' }}
                                    </td>

                                    <td>
                                        <span class="status-badge status-{{ strtolower($record->status) }}">
                                            {{ ucfirst($record->status) }}
                                        </span>
                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td colspan="3" class="empty-state">
                                        No students currently present.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <div class="col-12 col-xl-6">

            <div class="content-card h-100">

                <div class="card-heading">
                    <div>
                        <h5>Recently Registered Students</h5>
                        <p>Latest student registrations</p>
                    </div>

                    <a
                        href="{{ route('modules.placeholder', ['module' => 'students']) }}"
                        class="view-all-link"
                    >
                        View All
                    </a>
                </div>

                <div class="table-responsive">

                    <table class="table dashboard-table align-middle">

                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Student ID</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($recentStudents as $student)

                                <tr>
                                    <td>
                                        <div class="table-person">
                                            <div class="table-avatar">
                                                {{ strtoupper(substr($student->first_name, 0, 1)) }}
                                            </div>

                                            <div>
                                                <strong>{{ $student->full_name }}</strong>
                                                <small>
                                                    {{ $student->course ?? 'No course assigned' }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>

                                    <td>{{ $student->student_id }}</td>

                                    <td>
                                        <span class="status-badge status-{{ strtolower($student->enrollment_status) }}">
                                            {{ ucfirst($student->enrollment_status) }}
                                        </span>
                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td colspan="3" class="empty-state">
                                        No students registered yet.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <div class="col-12 col-xl-6">

            <div class="content-card h-100">

                <div class="card-heading">
                    <div>
                        <h5>Recent SMS Notifications</h5>
                        <p>Latest notification records</p>
                    </div>

                    <a
                        href="{{ route('modules.placeholder', ['module' => 'sms']) }}"
                        class="view-all-link"
                    >
                        View All
                    </a>
                </div>

                <div class="table-responsive">

                    <table class="table dashboard-table align-middle">

                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Recipient</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($recentSms as $sms)

                                <tr>
                                    <td>
                                        {{ $sms->student?->full_name ?? '—' }}
                                    </td>

                                    <td>{{ $sms->recipient }}</td>

                                    <td>
                                        <span class="status-badge status-{{ strtolower($sms->status) }}">
                                            {{ ucfirst($sms->status) }}
                                        </span>
                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td colspan="3" class="empty-state">
                                        No SMS notifications recorded yet.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <div class="col-12 col-xl-6">

            <div class="content-card h-100">

                <div class="card-heading">
                    <div>
                        <h5>Device Activities</h5>
                        <p>Registered attendance devices</p>
                    </div>

                    <a
                        href="{{ route('modules.placeholder', ['module' => 'devices']) }}"
                        class="view-all-link"
                    >
                        View All
                    </a>
                </div>

                <div class="table-responsive">

                    <table class="table dashboard-table align-middle">

                        <thead>
                            <tr>
                                <th>Device</th>
                                <th>Device ID</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($devices as $device)

                                <tr>
                                    <td>
                                        <strong>{{ $device->name }}</strong>

                                        <small class="d-block text-muted">
                                            {{ $device->model ?? 'Model not specified' }}
                                        </small>
                                    </td>

                                    <td>{{ $device->device_id }}</td>

                                    <td>
                                        <span class="status-badge status-{{ strtolower($device->status) }}">
                                            {{ ucfirst($device->status) }}
                                        </span>
                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td colspan="3" class="empty-state">
                                        No devices registered yet.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

    {{-- Chart data is generated from the database in DashboardController. --}}
    <script type="application/json" id="attendance-chart-data">
        @json($attendanceChart)
    </script>

    <script type="application/json" id="status-chart-data">
        @json($statusChart)
    </script>

    <script type="application/json" id="course-chart-data">
        @json($courseChart)
    </script>

    <script type="application/json" id="sms-chart-data">
        @json($smsChart)
    </script>

@endsection
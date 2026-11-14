<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Device;
use App\Models\SmsNotification;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();
        $now = Carbon::now();

        /*
        |--------------------------------------------------------------------------
        | Student Statistics
        |--------------------------------------------------------------------------
        */

        $activeStudents = Student::where(
            'enrollment_status',
            'active'
        )->count();

        /*
        |--------------------------------------------------------------------------
        | Today's Attendance Statistics
        |--------------------------------------------------------------------------
        */

        $todayAttendance = Attendance::whereDate(
            'attendance_date',
            $today
        );

        $present = (clone $todayAttendance)
            ->where('status', 'present')
            ->count();

        $late = (clone $todayAttendance)
            ->where('status', 'late')
            ->count();

        $absent = (clone $todayAttendance)
            ->where('status', 'absent')
            ->count();

        $excused = (clone $todayAttendance)
            ->where('status', 'excused')
            ->count();

        $currentlyPresent = (clone $todayAttendance)
            ->whereNotNull('time_in')
            ->whereNull('time_out')
            ->whereIn('status', ['present', 'late'])
            ->count();

        $attendanceRate = $activeStudents > 0
            ? round((($present + $late) / $activeStudents) * 100, 1)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | SMS Statistics
        |--------------------------------------------------------------------------
        */

        $smsSent = SmsNotification::whereDate('created_at', $today)
            ->whereNotNull('sent_at')
            ->count();

        $smsDelivered = SmsNotification::whereDate('created_at', $today)
            ->where('status', 'delivered')
            ->count();

        $smsFailed = SmsNotification::whereDate('created_at', $today)
            ->where('status', 'failed')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Device Statistics
        |--------------------------------------------------------------------------
        */

        $connectedDevices = Device::where('is_active', true)
            ->where('status', 'online')
            ->where(
                'last_heartbeat_at',
                '>=',
                $now->copy()->subMinutes(2)
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Dashboard Cards
        |--------------------------------------------------------------------------
        */

        $cards = [
            [
                'label' => 'Total Students',
                'value' => number_format($activeStudents),
                'icon' => 'bi-people',
                'color' => 'orange',
            ],
            [
                'label' => 'Present Today',
                'value' => number_format($present),
                'icon' => 'bi-person-check',
                'color' => 'green',
            ],
            [
                'label' => 'Late Today',
                'value' => number_format($late),
                'icon' => 'bi-clock-history',
                'color' => 'yellow',
            ],
            [
                'label' => 'Absent Today',
                'value' => number_format($absent),
                'icon' => 'bi-person-x',
                'color' => 'red',
            ],
            [
                'label' => 'Excused Today',
                'value' => number_format($excused),
                'icon' => 'bi-calendar-check',
                'color' => 'blue',
            ],
            [
                'label' => 'Currently Present',
                'value' => number_format($currentlyPresent),
                'icon' => 'bi-person-badge',
                'color' => 'purple',
            ],
            [
                'label' => 'Attendance Rate',
                'value' => $attendanceRate . '%',
                'icon' => 'bi-graph-up-arrow',
                'color' => 'green',
            ],
            [
                'label' => 'SMS Sent',
                'value' => number_format($smsSent),
                'icon' => 'bi-chat-dots',
                'color' => 'blue',
            ],
            [
                'label' => 'SMS Delivered',
                'value' => number_format($smsDelivered),
                'icon' => 'bi-check-circle',
                'color' => 'green',
            ],
            [
                'label' => 'SMS Failed',
                'value' => number_format($smsFailed),
                'icon' => 'bi-exclamation-circle',
                'color' => 'red',
            ],
            [
                'label' => 'Connected Devices',
                'value' => number_format($connectedDevices),
                'icon' => 'bi-cpu',
                'color' => 'purple',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Attendance Chart - Last Seven Days
        |--------------------------------------------------------------------------
        */

        $startDate = $today->copy()->subDays(6);

        $attendanceRows = Attendance::whereBetween(
            'attendance_date',
            [
                $startDate->toDateString(),
                $today->toDateString(),
            ]
        )->get([
            'attendance_date',
            'status',
        ]);

        $dailyLabels = [];
        $dailyPresent = [];
        $dailyLate = [];
        $dailyAbsent = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dateKey = $date->toDateString();

            $records = $attendanceRows->filter(
                fn ($row) => Carbon::parse(
                    $row->attendance_date
                )->toDateString() === $dateKey
            );

            $dailyLabels[] = $date->format('M d');

            $dailyPresent[] = $records
                ->where('status', 'present')
                ->count();

            $dailyLate[] = $records
                ->where('status', 'late')
                ->count();

            $dailyAbsent[] = $records
                ->where('status', 'absent')
                ->count();
        }

        $attendanceChart = [
            'labels' => $dailyLabels,
            'present' => $dailyPresent,
            'late' => $dailyLate,
            'absent' => $dailyAbsent,
        ];

        /*
        |--------------------------------------------------------------------------
        | Attendance Status Chart
        |--------------------------------------------------------------------------
        */

        $statusChart = [
            'labels' => [
                'Present',
                'Late',
                'Absent',
                'Excused',
            ],
            'values' => [
                $present,
                $late,
                $absent,
                $excused,
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Attendance By Course Chart
        |--------------------------------------------------------------------------
        */

        $courseRows = Attendance::query()
            ->join(
                'students',
                'attendance.student_id',
                '=',
                'students.id'
            )
            ->whereDate('attendance.attendance_date', $today)
            ->select(
                DB::raw(
                    "COALESCE(students.course, 'Unassigned') as course"
                ),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('students.course')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        $courseChart = [
            'labels' => $courseRows->pluck('course')->values(),
            'values' => $courseRows->pluck('total')->values(),
        ];

        /*
        |--------------------------------------------------------------------------
        | SMS Activity Chart - Last Seven Days
        |--------------------------------------------------------------------------
        */

        $smsRows = SmsNotification::whereBetween(
            'created_at',
            [
                $startDate->copy()->startOfDay(),
                $today->copy()->endOfDay(),
            ]
        )->get([
            'created_at',
            'status',
        ]);

        $smsLabels = [];
        $smsQueued = [];
        $smsDeliveredChart = [];
        $smsFailedChart = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dateKey = $date->toDateString();

            $records = $smsRows->filter(
                fn ($row) => $row->created_at->toDateString() === $dateKey
            );

            $smsLabels[] = $date->format('M d');

            $smsQueued[] = $records
                ->whereIn('status', [
                    'pending',
                    'queued',
                    'submitted',
                ])
                ->count();

            $smsDeliveredChart[] = $records
                ->where('status', 'delivered')
                ->count();

            $smsFailedChart[] = $records
                ->where('status', 'failed')
                ->count();
        }

        $smsChart = [
            'labels' => $smsLabels,
            'queued' => $smsQueued,
            'delivered' => $smsDeliveredChart,
            'failed' => $smsFailedChart,
        ];

        /*
        |--------------------------------------------------------------------------
        | Recent Attendance
        |--------------------------------------------------------------------------
        */

        $recentAttendance = Attendance::with('student')
            ->whereDate('attendance_date', $today)
            ->latest('created_at')
            ->limit(8)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Recently Registered Students
        |--------------------------------------------------------------------------
        */

        $recentStudents = Student::latest()
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Recent SMS Notifications
        |--------------------------------------------------------------------------
        */

        $recentSms = SmsNotification::with([
            'student',
            'guardian',
        ])
            ->latest()
            ->limit(6)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Registered Devices
        |--------------------------------------------------------------------------
        */

        $devices = Device::latest('updated_at')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Currently Present Students
        |--------------------------------------------------------------------------
        */

        $currentlyPresentStudents = Attendance::with('student')
            ->whereDate('attendance_date', $today)
            ->whereNotNull('time_in')
            ->whereNull('time_out')
            ->whereIn('status', ['present', 'late'])
            ->latest('time_in')
            ->limit(6)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Dashboard View
        |--------------------------------------------------------------------------
        */

        return view('dashboard.index', [
            'cards' => $cards,
            'attendanceChart' => $attendanceChart,
            'statusChart' => $statusChart,
            'courseChart' => $courseChart,
            'smsChart' => $smsChart,
            'recentAttendance' => $recentAttendance,
            'recentStudents' => $recentStudents,
            'recentSms' => $recentSms,
            'devices' => $devices,
            'currentlyPresentStudents' => $currentlyPresentStudents,
        ]);
    }
}

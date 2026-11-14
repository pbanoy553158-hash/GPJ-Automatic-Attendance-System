<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ModulePlaceholderController extends Controller
{
    public function show(Request $request, string $module): View
    {
        $modules = [
            'students' => [
                'title' => 'Student Management',
                'phase' => 'Phase 3',
                'description' => 'Student registration, profiles, enrollment, and student records.',
            ],

            'guardians' => [
                'title' => 'Guardian Management',
                'phase' => 'Phase 3',
                'description' => 'Guardian profiles, student assignments, and SMS consent.',
            ],

            'rfid' => [
                'title' => 'RFID Management',
                'phase' => 'Phase 3',
                'description' => 'RFID card registration, assignment, replacement, and deactivation.',
            ],

            'fingerprints' => [
                'title' => 'Fingerprint Registration',
                'phase' => 'Phase 4',
                'description' => 'AS608 enrollment and fingerprint template management.',
            ],

            'monitoring' => [
                'title' => 'Attendance Monitoring',
                'phase' => 'Phase 5',
                'description' => 'Live attendance events and verification results.',
            ],

            'records' => [
                'title' => 'Attendance Records',
                'phase' => 'Phase 5',
                'description' => 'Attendance history, corrections, and record management.',
            ],

            'schedules' => [
                'title' => 'Schedule Management',
                'phase' => 'Phase 5',
                'description' => 'School schedules, late thresholds, and attendance cut-offs.',
            ],

            'reports' => [
                'title' => 'Reports and Analytics',
                'phase' => 'Phase 9',
                'description' => 'Attendance reports and PDF/CSV exports.',
            ],

            'sms' => [
                'title' => 'SMS Notifications',
                'phase' => 'Phase 6',
                'description' => 'SMS templates, gateway integration, and notification history.',
            ],

            'devices' => [
                'title' => 'Device Management',
                'phase' => 'Phase 9',
                'description' => 'ESP32 device registration, heartbeat, and device status.',
            ],

            'users' => [
                'title' => 'User Management',
                'phase' => 'Phase 9',
                'description' => 'Administrator and attendance staff accounts.',
            ],

            'settings' => [
                'title' => 'System Settings',
                'phase' => 'Phase 9',
                'description' => 'School details, academic year, attendance rules, and configuration.',
            ],

            'audit-logs' => [
                'title' => 'Audit Logs',
                'phase' => 'Phase 9',
                'description' => 'System activity and administrative change history.',
            ],
        ];

        if (!isset($modules[$module])) {
            throw new NotFoundHttpException();
        }

        $adminOnlyModules = [
            'users',
            'settings',
            'audit-logs',
        ];

        $user = $request->user();

        if (
            in_array($module, $adminOnlyModules, true) &&
            !$user->isAdmin()
        ) {
            abort(403, 'Administrator access is required.');
        }

        return view('modules.placeholder', [
            'module' => $modules[$module],
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\Setting;
use App\Models\SmsTemplate;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AttendanceSystemSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@example.com')],
            [
                'name' => env('ADMIN_NAME', 'System Administrator'),
                'password' => Hash::make(
                    env('ADMIN_PASSWORD', 'ChangeMe123!')
                ),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        $settings = [
            'school_name' => 'Automatic Student Attendance System',
            'school_address' => '',
            'timezone' => 'Asia/Manila',
            'academic_year' => '2026-2027',
            'semester' => '1st Semester',
            'sms_enabled' => '0',
        ];

        foreach ($settings as $key => $value) {
            Setting::firstOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'group' => 'general',
                ]
            );
        }

        Schedule::firstOrCreate(
            ['name' => 'Default School Schedule'],
            [
                'course' => null,
                'year_level' => null,
                'section' => null,
                'school_start_time' => '07:00:00',
                'attendance_start_time' => '07:00:00',
                'late_threshold' => '07:30:00',
                'attendance_cutoff' => '08:00:00',
                'time_out_time' => '16:30:00',
                'school_days' => [
                    'monday',
                    'tuesday',
                    'wednesday',
                    'thursday',
                    'friday',
                ],
                'academic_year' => '2026-2027',
                'semester' => '1st Semester',
                'is_active' => true,
            ]
        );

        $templates = [
            [
                'event_type' => 'present',
                'name' => 'Present Notification',
                'message' => 'Good day! Your child {student_name} has successfully arrived at school at {time}.',
            ],
            [
                'event_type' => 'late',
                'name' => 'Late Arrival Notification',
                'message' => 'Good day! Your child {student_name} arrived late at {time}.',
            ],
            [
                'event_type' => 'absent',
                'name' => 'Absence Notification',
                'message' => 'Good day! Your child {student_name} was marked absent today.',
            ],
            [
                'event_type' => 'time_out',
                'name' => 'Time Out Notification',
                'message' => 'Good day! Your child {student_name} recorded Time Out at {time}.',
            ],
        ];

        foreach ($templates as $template) {
            SmsTemplate::firstOrCreate(
                ['event_type' => $template['event_type']],
                [
                    'name' => $template['name'],
                    'message' => $template['message'],
                    'is_enabled' => true,
                ]
            );
        }
    }
}
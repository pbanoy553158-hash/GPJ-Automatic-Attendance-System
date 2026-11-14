<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendance';

    protected $fillable = [
        'student_id',
        'schedule_id',
        'device_id',
        'attendance_date',
        'time_in',
        'time_out',
        'duration_minutes',
        'status',
        'verification_method',
        'fingerprint_template_id',
        'rfid_uid',
        'device_event_id',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'attendance_date' => 'date',
            'time_in' => 'datetime',
            'time_out' => 'datetime',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function smsNotifications()
    {
        return $this->hasMany(SmsNotification::class);
    }
}
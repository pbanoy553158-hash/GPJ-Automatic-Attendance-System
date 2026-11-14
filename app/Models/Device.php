<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'device_id',
        'name',
        'model',
        'location',
        'device_token',
        'status',
        'last_heartbeat_at',
        'last_attendance_at',
        'last_error',
        'is_active',
    ];

    protected $hidden = [
        'device_token',
    ];

    protected function casts(): array
    {
        return [
            'last_heartbeat_at' => 'datetime',
            'last_attendance_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class);
    }
}
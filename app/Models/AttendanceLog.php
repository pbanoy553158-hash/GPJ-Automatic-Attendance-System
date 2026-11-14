<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    protected $fillable = [
        'student_id',
        'device_id',
        'event_type',
        'rfid_uid',
        'fingerprint_template_id',
        'rfid_verified',
        'fingerprint_verified',
        'result',
        'message',
        'metadata',
        'event_at',
    ];

    protected function casts(): array
    {
        return [
            'rfid_verified' => 'boolean',
            'fingerprint_verified' => 'boolean',
            'metadata' => 'array',
            'event_at' => 'datetime',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
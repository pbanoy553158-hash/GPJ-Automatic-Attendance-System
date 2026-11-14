<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsNotification extends Model
{
    protected $fillable = [
        'student_id',
        'guardian_id',
        'attendance_id',
        'event_type',
        'recipient',
        'message',
        'provider',
        'provider_message_id',
        'status',
        'retry_count',
        'gateway_response',
        'sent_at',
        'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function guardian()
    {
        return $this->belongsTo(Guardian::class);
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
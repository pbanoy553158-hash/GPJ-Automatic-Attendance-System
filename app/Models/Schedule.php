<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'name',
        'course',
        'year_level',
        'section',
        'school_start_time',
        'attendance_start_time',
        'late_threshold',
        'attendance_cutoff',
        'time_out_time',
        'school_days',
        'academic_year',
        'semester',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'school_days' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }
}
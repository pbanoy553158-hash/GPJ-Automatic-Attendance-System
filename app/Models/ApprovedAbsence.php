<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovedAbsence extends Model
{
    protected $fillable = [
        'student_id',
        'absence_date',
        'reason',
        'remarks',
        'approved_by',
    ];

    protected function casts(): array
    {
        return [
            'absence_date' => 'date',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
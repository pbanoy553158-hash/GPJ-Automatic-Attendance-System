<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RfidCard extends Model
{
    protected $fillable = [
        'student_id',
        'uid',
        'status',
        'registered_at',
        'deactivated_at',
    ];

    protected function casts(): array
    {
        return [
            'registered_at' => 'datetime',
            'deactivated_at' => 'datetime',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
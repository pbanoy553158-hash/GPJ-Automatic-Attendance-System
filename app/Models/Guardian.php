<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guardian extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'full_name',
        'contact_number',
        'secondary_contact_number',
        'email',
        'sms_consent',
        'phone_verified',
        'is_active',
        'notification_preferences',
    ];

    protected function casts(): array
    {
        return [
            'sms_consent' => 'boolean',
            'phone_verified' => 'boolean',
            'is_active' => 'boolean',
            'notification_preferences' => 'array',
        ];
    }

    public function students()
    {
        return $this->belongsToMany(Student::class)
            ->withPivot(['relationship', 'is_primary'])
            ->withTimestamps();
    }

    public function smsNotifications()
    {
        return $this->hasMany(SmsNotification::class);
    }
}
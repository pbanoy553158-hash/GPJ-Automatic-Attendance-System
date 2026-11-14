<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Extend Laravel's default users table.
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('staff')->after('password');
            $table->boolean('is_active')->default(true)->after('role');
        });

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('course')->nullable()->index();
            $table->string('year_level')->nullable();
            $table->string('section')->nullable();
            $table->string('email')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('enrollment_status')->default('active')->index();
            $table->date('registration_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('guardians', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('contact_number');
            $table->string('secondary_contact_number')->nullable();
            $table->string('email')->nullable();
            $table->boolean('sms_consent')->default(false);
            $table->boolean('phone_verified')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('notification_preferences')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('student_guardian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();
            $table->foreignId('guardian_id')
                ->constrained('guardians')
                ->cascadeOnDelete();
            $table->string('relationship')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->unique(['student_id', 'guardian_id']);
        });

        Schema::create('rfid_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();
            $table->string('uid')->unique();
            $table->string('status')->default('active')->index();
            $table->timestamp('registered_at')->nullable();
            $table->timestamp('deactivated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('fingerprint_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();
            $table->string('device_id')->nullable();
            $table->unsignedInteger('template_id');
            $table->string('status')->default('active')->index();
            $table->timestamp('registered_at')->nullable();
            $table->timestamp('deactivated_at')->nullable();
            $table->timestamps();

            $table->unique(['device_id', 'template_id']);
        });

        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('course')->nullable()->index();
            $table->string('year_level')->nullable();
            $table->string('section')->nullable();
            $table->time('school_start_time');
            $table->time('attendance_start_time');
            $table->time('late_threshold');
            $table->time('attendance_cutoff');
            $table->time('time_out_time');
            $table->json('school_days');
            $table->string('academic_year')->nullable();
            $table->string('semester')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('approved_absences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();
            $table->date('absence_date');
            $table->string('reason');
            $table->text('remarks')->nullable();
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamps();

            $table->unique(['student_id', 'absence_date']);
        });

        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('device_id')->unique();
            $table->string('name');
            $table->string('model')->nullable();
            $table->string('location')->nullable();
            $table->string('device_token', 128)->unique();
            $table->string('status')->default('offline')->index();
            $table->timestamp('last_heartbeat_at')->nullable();
            $table->timestamp('last_attendance_at')->nullable();
            $table->text('last_error')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();
            $table->foreignId('schedule_id')
                ->nullable()
                ->constrained('schedules')
                ->nullOnDelete();
            $table->foreignId('device_id')
                ->nullable()
                ->constrained('devices')
                ->nullOnDelete();
            $table->date('attendance_date');
            $table->dateTime('time_in')->nullable();
            $table->dateTime('time_out')->nullable();
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->string('status')->default('present')->index();
            $table->string('verification_method')->default('rfid_fingerprint');
            $table->unsignedInteger('fingerprint_template_id')->nullable();
            $table->string('rfid_uid')->nullable();
            $table->string('device_event_id')->nullable()->unique();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(
                ['student_id', 'schedule_id', 'attendance_date'],
                'attendance_student_schedule_date_unique'
            );

            $table->index(['attendance_date', 'status']);
        });

        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                ->nullable()
                ->constrained('students')
                ->nullOnDelete();
            $table->foreignId('device_id')
                ->nullable()
                ->constrained('devices')
                ->nullOnDelete();
            $table->string('event_type');
            $table->string('rfid_uid')->nullable();
            $table->unsignedInteger('fingerprint_template_id')->nullable();
            $table->boolean('rfid_verified')->default(false);
            $table->boolean('fingerprint_verified')->default(false);
            $table->string('result')->index();
            $table->text('message')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('event_at')->nullable();
            $table->timestamps();

            $table->index(['event_type', 'created_at']);
        });

        Schema::create('sms_templates', function (Blueprint $table) {
            $table->id();
            $table->string('event_type')->unique();
            $table->string('name');
            $table->text('message');
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });

        Schema::create('sms_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                ->nullable()
                ->constrained('students')
                ->nullOnDelete();
            $table->foreignId('guardian_id')
                ->nullable()
                ->constrained('guardians')
                ->nullOnDelete();
            $table->foreignId('attendance_id')
                ->nullable()
                ->constrained('attendance')
                ->nullOnDelete();
            $table->string('event_type');
            $table->string('recipient');
            $table->text('message');
            $table->string('provider')->nullable();
            $table->string('provider_message_id')->nullable();
            $table->string('status')->default('pending')->index();
            $table->unsignedInteger('retry_count')->default(0);
            $table->text('gateway_response')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            $table->index(['event_type', 'created_at']);
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->string('action');
            $table->string('module')->nullable();
            $table->string('record_type')->nullable();
            $table->unsignedBigInteger('record_id')->nullable();
            $table->text('description')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['module', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('sms_notifications');
        Schema::dropIfExists('sms_templates');
        Schema::dropIfExists('attendance_logs');
        Schema::dropIfExists('attendance');
        Schema::dropIfExists('devices');
        Schema::dropIfExists('approved_absences');
        Schema::dropIfExists('schedules');
        Schema::dropIfExists('fingerprint_templates');
        Schema::dropIfExists('rfid_cards');
        Schema::dropIfExists('student_guardian');
        Schema::dropIfExists('guardians');
        Schema::dropIfExists('students');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_active']);
        });
    }
};
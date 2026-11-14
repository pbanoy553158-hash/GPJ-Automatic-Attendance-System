<?php

namespace App\Services;

use App\Models\AttendanceLog;
use App\Models\FingerprintTemplate;
use App\Models\RfidCard;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class DualVerificationService
{
    public function verify(
        string $deviceId,
        string $eventId,
        string $rfidUid,
        int $fingerprintTemplateId,
        ?int $confidence = null
    ): array {
        return DB::transaction(function () use (
            $deviceId,
            $eventId,
            $rfidUid,
            $fingerprintTemplateId,
            $confidence
        ) {
            $rfidUid = strtoupper(
                preg_replace('/[^A-F0-9]/i', '', $rfidUid)
            );

            $card = RfidCard::with('student')
                ->where('uid', $rfidUid)
                ->where('status', 'active')
                ->first();

            $fingerprint = FingerprintTemplate::with('student')
                ->where('device_id', $deviceId)
                ->where('template_id', $fingerprintTemplateId)
                ->where('status', 'active')
                ->first();

            $rfidVerified = $card !== null;
            $fingerprintVerified = $fingerprint !== null;

            $student = null;
            $result = 'rejected';
            $message = 'Verification failed.';

            if (!$rfidVerified && !$fingerprintVerified) {
                $message = 'RFID card and fingerprint were not registered.';
            } elseif (!$rfidVerified) {
                $message = 'RFID card is not registered or is inactive.';
            } elseif (!$fingerprintVerified) {
                $message = 'Fingerprint is not registered on this device or is inactive.';
            } elseif ($card->student_id !== $fingerprint->student_id) {
                $message = 'RFID and fingerprint belong to different students.';
            } else {
                $student = Student::whereKey($card->student_id)
                    ->where('enrollment_status', 'active')
                    ->first();

                if (!$student) {
                    $message = 'Student is not currently active.';
                } else {
                    $result = 'verified';
                    $message = 'RFID and fingerprint successfully matched.';
                }
            }

            AttendanceLog::create([
                'student_id' => $student?->id
                    ?? $card?->student_id
                    ?? $fingerprint?->student_id,
                'device_id' => $deviceId,
                'event_type' => 'dual_verification',
                'rfid_uid' => $rfidUid,
                'fingerprint_template_id' => $fingerprintTemplateId,
                'rfid_verified' => $rfidVerified,
                'fingerprint_verified' => $fingerprintVerified,
                'result' => $result,
                'message' => $message,
                'metadata' => [
                    'event_id' => $eventId,
                    'confidence' => $confidence,
                    'rfid_student_id' => $card?->student_id,
                    'fingerprint_student_id' => $fingerprint?->student_id,
                ],
                'event_at' => now(),
            ]);

            return [
                'success' => $result === 'verified',
                'result' => $result,
                'message' => $message,
                'event_id' => $eventId,
                'student' => $student ? [
                    'id' => $student->id,
                    'student_id' => $student->student_id,
                    'name' => $student->full_name,
                ] : null,
            ];
        });
    }
}
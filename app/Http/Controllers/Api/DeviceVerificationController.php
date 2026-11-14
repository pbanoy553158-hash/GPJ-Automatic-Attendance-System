<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DualVerificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DeviceVerificationController extends Controller
{
    public function verify(
        Request $request,
        DualVerificationService $verificationService
    ): JsonResponse {
        $validated = $request->validate([
            'event_id' => ['required', 'uuid'],
            'rfid_uid' => ['required', 'string', 'max:32'],
            'fingerprint_template_id' => [
                'required',
                'integer',
                'min:1',
                'max:65535',
            ],
            'confidence' => ['nullable', 'integer', 'min:0'],
        ]);

        $device = $request->attributes->get('attendance_device');

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Device authentication failed.',
            ], 401);
        }

        $result = $verificationService->verify(
            $device->device_id,
            $validated['event_id'],
            $validated['rfid_uid'],
            (int) $validated['fingerprint_template_id'],
            isset($validated['confidence'])
                ? (int) $validated['confidence']
                : null
        );

        $device->forceFill([
            'last_attendance_at' => now(),
            'last_error' => $result['success']
                ? null
                : $result['message'],
        ])->save();

        return response()->json($result, $result['success'] ? 200 : 422);
    }
}
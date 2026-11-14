<?php

namespace App\Http\Middleware;

use App\Models\Device;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateDevice
{
    public function handle(Request $request, Closure $next): Response
    {
        $deviceId = $request->header('X-Device-ID');
        $token = $request->bearerToken();

        if (!$deviceId || !$token) {
            return response()->json([
                'success' => false,
                'message' => 'Missing device credentials.',
            ], 401);
        }

        $device = Device::where('device_id', $deviceId)
            ->where('is_active', true)
            ->first();

        if (!$device || !hash_equals(
            (string) $device->device_token,
            (string) $token
        )) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid device credentials.',
            ], 401);
        }

        $device->forceFill([
            'last_heartbeat_at' => now(),
            'status' => 'online',
        ])->save();

        $request->attributes->set('attendance_device', $device);

        return $next($request);
    }
}
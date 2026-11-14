<?php

use App\Http\Controllers\Api\DeviceVerificationController;
use App\Http\Middleware\AuthenticateDevice;
use Illuminate\Support\Facades\Route;

Route::middleware(AuthenticateDevice::class)
    ->prefix('device')
    ->group(function () {
        Route::post('/verify', [
            DeviceVerificationController::class,
            'verify',
        ])->name('api.device.verify');
    });
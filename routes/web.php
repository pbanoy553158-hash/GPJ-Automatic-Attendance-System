
<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\RfidCardController;
use App\Http\Controllers\FingerprintController;
use App\Http\Controllers\ModulePlaceholderController;
use App\Http\Middleware\EnsureUserHasRole;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [
        LoginController::class,
        'showLoginForm',
    ])->name('login');

    Route::post('/login', [
        LoginController::class,
        'login',
    ])
        ->middleware('throttle:5,1')
        ->name('login.submit');

});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    EnsureUserHasRole::class . ':admin,staff',
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [
        DashboardController::class,
        'index',
    ])->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Student Management
    |--------------------------------------------------------------------------
    */

    Route::resource('students', StudentController::class);


    /*
    |--------------------------------------------------------------------------
    | Guardian Management
    |--------------------------------------------------------------------------
    */

    Route::resource('guardians', GuardianController::class);


    /*
    |--------------------------------------------------------------------------
    | RFID Management
    |--------------------------------------------------------------------------
    */

    Route::resource('rfid', RfidCardController::class)
        ->parameters([
            'rfid' => 'rfid',
        ]);


    /*
    |--------------------------------------------------------------------------
    | Fingerprint Management
    |--------------------------------------------------------------------------
    */

    Route::resource('fingerprints', FingerprintController::class)
        ->except([
            'show',
        ]);


    /*
    |--------------------------------------------------------------------------
    | Placeholder Modules
    |--------------------------------------------------------------------------
    |
    | Modules that have not yet been implemented use this route.
    |
    */

    Route::get('/modules/{module}', [
        ModulePlaceholderController::class,
        'show',
    ])->name('modules.placeholder');


    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [
        LoginController::class,
        'logout',
    ])->name('logout');

});

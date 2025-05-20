<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TimesheetController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', function () {
        $todayAttendance = app(AttendanceController::class)->getTodayAttendance();
        $currentAttendance = app(AttendanceController::class)->getCurrentAttendance();
        return view('main.dashboard', [
            'currentAttendance' => $currentAttendance,
            'todayAttendance' => $todayAttendance
        ]);

    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance');
    Route::get('/attendancelist', [AttendanceController::class, 'pending'])->name('attendance.pending');

    Route::put('/attendance/{attendance}/approve', [AttendanceController::class, 'approve'])->name('attendance.approve');
    Route::put('/attendance/{attendance}/reject', [AttendanceController::class, 'reject'])->name('attendance.reject');


    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.checkout');

    Route::get('/timesheets', [TimesheetController::class, 'index'])->name('timesheets.index');
    Route::get('/timesheets/create', [TimesheetController::class, 'create'])->name('timesheets.create');
    Route::post('/timesheets', [TimesheetController::class, 'store'])->name('timesheets.store');

    Route::get('/ts', function () {
        return view('timesheets.index');
    })->name('ts');
});



Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/checkin', function () {
    return view('main.check');
});

Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
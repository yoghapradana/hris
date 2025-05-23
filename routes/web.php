<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TimesheetController;
use App\Http\Middleware\EnsureUserIsAuthenticated;



Route::middleware([EnsureUserIsAuthenticated::class])->group(function () {
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

    // Edit current user's profile
    //Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // profile.show must in the bottom of the route list
    // or it will error because any string after profile/ will mistaken as {user} variable
    Route::get('/profile/index', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit/{user?}', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update/{user}', [ProfileController::class, 'updateOther'])->name('profile.update.other');
    Route::get('/profile/{user?}', [ProfileController::class, 'show'])->name('profile.show');
    
    


    
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance');
    Route::get('/attendance/{id}', [AttendanceController::class, 'show'])->name('attendance.show');
    Route::get('/attendancelist', [AttendanceController::class, 'pending'])->name('attendance.pending');

    Route::put('/attendance/{attendance}/approve', [AttendanceController::class, 'approve'])->name('attendance.approve');
    Route::put('/attendance/{attendance}/reject', [AttendanceController::class, 'reject'])->name('attendance.reject');


    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.checkout');

    Route::resource('timesheets', TimesheetController::class)->only([
        'index', 'create', 'store'
    ]);


    Route::get('/ts', function () {
        return view('timesheets.index');
    })->name('ts');
});



Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/checkin', function () {
    return view('main.check');
});

Route::get('/test/{view}', function ($view) {
    $path = 'test.' . str_replace('/', '.', $view);

    if (view()->exists($path)) {
        return view($path);
    }

    abort(404);
})->where('view', '.*');
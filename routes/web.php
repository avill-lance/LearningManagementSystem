<?php

use App\Http\Controllers\Api\V1\AuthController as ApiAuthController;
use App\Http\Controllers\WebAuthController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'landing')->name('landing');
Route::view('/login', 'auth.login')->name('login');
Route::post('/login', [WebAuthController::class, 'login'])->name('login.authenticate');
Route::view('/signup', 'auth.signup')->name('signup');

// Student Registration Routes
Route::get('/signup/student', [WebAuthController::class, 'showStudentRegistrationForm'])->name('auth.register.student.get');
Route::post('/signup/student', [WebAuthController::class, 'registerStudent'])->name('auth.register.student');

// Teacher Registration Routes
Route::get('/signup/teacher', [WebAuthController::class, 'showTeacherRegistrationForm'])->name('auth.register.teacher.get');
Route::post('/signup/teacher', [WebAuthController::class, 'registerTeacher'])->name('auth.register.teacher');

Route::middleware('auth')->group(function () {
	Route::get('/admin/', [WebAuthController::class, 'adminDashboard'])->name('admin.dashboard');
	Route::get('/teacher/', [WebAuthController::class, 'teacherDashboard'])->name('teacher.dashboard');
	Route::get('/student/', [WebAuthController::class, 'studentDashboard'])->name('student.dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/admin/', [WebAuthController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/teacher/', [WebAuthController::class, 'teacherDashboard'])->name('teacher.dashboard');
    Route::get('/student/', [WebAuthController::class, 'studentDashboard'])->name('student.dashboard');
    Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');
    
    // Password change routes
    Route::get('/password/change', [WebAuthController::class, 'editPassword'])->name('password.change');
    Route::post('/password/change', [WebAuthController::class, 'updatePassword'])->name('password.update');
});
	Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');
});

Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');
Route::view('/reset-password/{token}', 'auth.reset-password')->name('password.reset');
Route::post('/forgot-password', [ApiAuthController::class, 'forgotPassword'])->name('password.email');
Route::post('/reset-password', [ApiAuthController::class, 'resetPassword'])->name('password.update');

Route::middleware(['auth:admin', 'admin'])->group(function () {
    Route::get('/admin/register', [App\Http\Controllers\Admin\AuthController::class, 'showRegistrationForm'])
        ->name('admin.register');
    Route::post('/admin/register', [App\Http\Controllers\Admin\AuthController::class, 'register'])
        ->name('admin.register.store');
});
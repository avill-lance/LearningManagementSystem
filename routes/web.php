<?php

use App\Http\Controllers\Api\V1\AuthController as ApiAuthController;
use App\Http\Controllers\WebAuthController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'landing')->name('landing');
Route::view('/login', 'auth.login')->name('login');
Route::post('/login', [WebAuthController::class, 'login'])->name('login.authenticate');
Route::view('/signup', 'auth.signup')->name('signup');

Route::middleware('auth')->group(function () {
	Route::get('/admin/', [WebAuthController::class, 'adminDashboard'])->name('admin.dashboard');
	Route::get('/admin/users', [WebAuthController::class, 'adminUsers'])->name('admin.users.index');
	Route::get('/admin/users/create', [WebAuthController::class, 'adminUsersCreate'])->name('admin.users.create');
	Route::post('/admin/users', [WebAuthController::class, 'adminUsersStore'])->name('admin.users.store');
	Route::get('/admin/users/{user}/edit', [WebAuthController::class, 'adminUsersEdit'])->name('admin.users.edit');
	Route::put('/admin/users/{user}', [WebAuthController::class, 'adminUsersUpdate'])->name('admin.users.update');
	Route::get('/admin/users/delete/{user}', [WebAuthController::class, 'adminUsersDelete'])->name('admin.users.delete');
	Route::get('/admin/users/restore/{user}', [WebAuthController::class, 'adminUsersRestore'])->name('admin.users.restore');
	Route::get('/admin/users/{user}', [WebAuthController::class, 'adminUsersShow'])->name('admin.users.show');
	Route::get('/teacher/', [WebAuthController::class, 'teacherDashboard'])->name('teacher.dashboard');
	Route::get('/student/', [WebAuthController::class, 'studentDashboard'])->name('student.dashboard');
	Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');
});

Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');
Route::view('/reset-password/{token}', 'auth.reset-password')->name('password.reset');
Route::post('/forgot-password', [ApiAuthController::class, 'forgotPassword'])->name('password.email');
Route::post('/reset-password', [ApiAuthController::class, 'resetPassword'])->name('password.update');
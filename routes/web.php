<?php

use App\Http\Controllers\c_auth;
use App\Http\Controllers\c_kelolaAkun;
use App\Http\Controllers\c_departemen;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return match (Auth::user()->nama_role) {
            'admin' => redirect()->route('admin.dashboard'),
            'manager' => redirect()->route('manager.dashboard'),
            'staff' => redirect()->route('staff.dashboard'),
            default => redirect('/login'),
        };
    }
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [c_auth::class, 'showLogin'])->name('login');
    Route::post('/login', [c_auth::class, 'login'])->name('login.submit');

    Route::get('/forgot-password', [c_auth::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [c_auth::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [c_auth::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [c_auth::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [c_auth::class, 'logout'])->name('logout');

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', function () { return view('admin.dashboard'); })->name('admin.dashboard');
        Route::resource('kelola-akun', c_kelolaAkun::class);
        Route::resource('departemen', c_departemen::class);
    });

    Route::middleware('role:manager')->group(function () {
        Route::get('/manager/dashboard', function () { return view('manager.dashboard'); })->name('manager.dashboard');
    });

    Route::middleware('role:staff')->group(function () {
        Route::get('/staff/dashboard', function () { return view('staff.dashboard'); })->name('staff.dashboard');
    });
});

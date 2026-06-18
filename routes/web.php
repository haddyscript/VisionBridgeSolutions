<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Portal\DashboardController;
use App\Http\Controllers\Portal\UploadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/portal', DashboardController::class)->name('portal.dashboard');
    Route::post('/portal/projects/{project}/uploads', [UploadController::class, 'store'])->name('portal.uploads.store');
    Route::delete('/portal/uploads/{upload}', [UploadController::class, 'destroy'])->name('portal.uploads.destroy');
});

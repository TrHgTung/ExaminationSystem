<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\AuthController as StudentAuthController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Lecturer\AuthController as LecturerAuthController;
use App\Models\Student;
use App\Models\Lecturer;
use App\Models\Admin;

Route::post('/login-student', [StudentAuthController::class, 'login']);
Route::post('/login-admin', [AdminAuthController::class, 'login']);
Route::post('/login-lecturer', [LecturerAuthController::class, 'login']);

Route::middleware('auth:sanctum', 'isAdmin')->group(function () {
    Route::post('/create-student', [StudentAuthController::class, 'createStudent']);
    Route::post('/create-lecturer', [AdminAuthController::class, 'createLecturer']);
});

Route::middleware('auth:sanctum', 'isStudent')->group(function () {
    Route::get('/profile-student', [StudentAuthController::class, 'profile']);
    Route::post('/logout-student', [StudentAuthController::class, 'logout']);
    Route::put('/update-profile-student', [StudentAuthController::class, 'updateProfile']);
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\AuthController as StudentAuthController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Lecturer\AuthController as LecturerAuthController;
use App\Http\Controllers\Admin\AccountController as AdminAccountController;
use App\Models\Student;
use App\Models\Lecturer;
use App\Models\Admin;

Route::post('/login-student', [StudentAuthController::class, 'login']);
Route::post('/login-admin', [AdminAuthController::class, 'login']);
Route::post('/login-lecturer', [LecturerAuthController::class, 'login']);

Route::middleware('auth:sanctum', 'isAdmin')->group(function () {
    Route::post('/create-student', [AdminAccountController::class, 'createStudent']);
    Route::post('/create-lecturer', [AdminAccountController::class, 'createLecturer']);
    Route::put('/change-password-admin', [AdminAuthController::class, 'changePassword']);
});

Route::middleware('auth:sanctum', 'isStudent')->group(function () {
    Route::get('/profile-student', [StudentAuthController::class, 'profile']);
    Route::post('/logout-student', [StudentAuthController::class, 'logout']);
    Route::put('/update-profile-student', [StudentAuthController::class, 'updateProfile']);
});

Route::middleware('auth:sanctum', 'isLecturer')->group(function () {
    Route::get('/all-exams', [StudentAuthController::class, 'getExamList']);
    Route::post('/create-question/{examID}', [StudentAuthController::class, 'createQuestion']);
    Route::post('/create-answer', [StudentAuthController::class, 'createAnswer']);
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\AuthController as StudentAuthController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Lecturer\AuthController as LecturerAuthController;
use App\Http\Controllers\Admin\AccountController as AdminAccountController;
use App\Http\Controllers\Admin\ExamController as AdminExamController;
use App\Http\Controllers\Lecturer\ExamController as LecturerExamController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Lecturer\DashboardController as LecturerDashboardController;
use App\Models\Student;
use App\Models\Lecturer;
use App\Models\Admin;

Route::post('/login-student', [StudentAuthController::class, 'login']);
Route::post('/login-admin', [AdminAuthController::class, 'login']);
Route::post('/login-lecturer', [LecturerAuthController::class, 'login']);

// // admin
Route::middleware('auth:sanctum', 'isAdmin')->group(function () {
    // Account
    Route::post('/create-student', [AdminAccountController::class, 'createStudent']);
    Route::post('/create-lecturer', [AdminAccountController::class, 'createLecturer']);
    // Auth
    Route::post('/logout-admin', [AdminAuthController::class, 'logout']);
    Route::get('/profile-admin', [AdminAuthController::class, 'viewProfile']);
    Route::patch('/change-password-admin', [AdminAuthController::class, 'changePassword']);
    // Dashboard
    Route::get('/all-students', [AdminDashboardController::class, 'getStudentList']);
    Route::get('/all-lecturers', [AdminDashboardController::class, 'getLecturerList']);
    Route::get('/all-exams', [AdminDashboardController::class, 'getExamList']);
    Route::get('/all-questions', [AdminDashboardController::class, 'getQuestion']);
    Route::get('/all-answers', [AdminDashboardController::class, 'getAnswer']);
    Route::delete('/remove-exam/{examID}', [AdminDashboardController::class, 'removeExam']);
    Route::delete('/remove-lecturer/{lecturerID}', [AdminDashboardController::class, 'removeLecturer']);
    Route::delete('/remove-student/{studentID}', [AdminDashboardController::class, 'removeStudent']);
    // Exam handling
    Route::post('/create-exam', [AdminExamController::class, 'createExam']);
    Route::patch('/update-exam/{examID}', [AdminExamController::class, 'updateExam']);
});

// students
Route::middleware('auth:sanctum', 'isStudent')->group(function () {
    // auth
    Route::get('/profile-student', [StudentAuthController::class, 'profile']);
    Route::post('/logout-student', [StudentAuthController::class, 'logout']);
    Route::patch('/update-profile-student', [StudentAuthController::class, 'updateProfile']);
});

// lecturers
Route::middleware('auth:sanctum', 'isLecturer')->group(function () {
    //auth
    Route::get('/profile-lecturer', [LecturerAuthController::class, 'viewProfile']);
    Route::post('/logout-lecturer', [LecturerAuthController::class, 'logout']);
    // dashboatd
    Route::get('/all-exams-l', [LecturerDashboardController::class, 'getExamList']);
    Route::get('/get-question', [LecturerDashboardController::class, 'getQuestion']);
    Route::get('/get-answer/{questionId}', [LecturerDashboardController::class, 'getAnswer']);
    Route::get('/get-student-l', [LecturerDashboardController::class, 'getStudent']);
    // exam handling
    Route::post('/create-question/{examID}', [LecturerExamController::class, 'createQuestion']);
    Route::post('/create-answer/{questionID}', [LecturerExamController::class, 'createAnswer']);
    Route::post('/update-question/{questionID}', [LecturerExamController::class, 'updateQuestion']);
    Route::post('/update-answer/{answerID}', [LecturerExamController::class, 'updateAnswer']);
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\StudentGuardianController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\CycleController;


// =====================
// Auth
// =====================
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:api');
});

// =====================
// Students (protégées)
// =====================
Route::middleware('auth:api')->prefix('students')->group(function () {
    Route::get('/', [StudentController::class, 'index']);
    Route::post('/', [StudentController::class, 'store']);
    Route::get('/{id}', [StudentController::class, 'show']);
    Route::put('/{id}', [StudentController::class, 'update']);
    Route::delete('/{id}', [StudentController::class, 'destroy']);
    Route::get('/{id}/download-pj', [StudentController::class, 'downloadPieceJointe']); // Téléchargement PJ

    // Relations avec guardians
    Route::get('/{student}/guardians', [StudentGuardianController::class, 'index']);
    Route::post('/guardians/attach', [StudentGuardianController::class, 'attach']);
    Route::post('/guardians/detach', [StudentGuardianController::class, 'detach']);
    Route::get('/guardians/{guardian}/students', [StudentGuardianController::class, 'getStudents']);
    Route::put('/{student}/guardians/{guardian}', [StudentGuardianController::class, 'updateRelation']);
});

// =====================
// Guardians (protégées)
// =====================
Route::middleware('auth:api')->prefix('guardians')->group(function () {
    Route::get('/', [GuardianController::class, 'index']);
    Route::post('/', [GuardianController::class, 'store']);
    Route::get('/{id}', [GuardianController::class, 'show']);
    Route::put('/{id}', [GuardianController::class, 'update']);
    Route::delete('/{id}', [GuardianController::class, 'destroy']);
});

// =====================
// Teachers (protégées)
// =====================
Route::middleware('auth:api')->prefix('teachers')->group(function () {
    Route::get('/', [TeacherController::class, 'index']);
    Route::post('/', [TeacherController::class, 'store']);
    Route::get('/{id}', [TeacherController::class, 'show']);
    Route::put('/{id}', [TeacherController::class, 'update']);
    Route::delete('/{id}', [TeacherController::class, 'destroy']);
});

// =====================
// Academic Years (protégées)
// =====================
Route::middleware('auth:api')->prefix('academic-years')->group(function () {
    Route::get('/', [AcademicYearController::class, 'index']);
    Route::post('/', [AcademicYearController::class, 'store']);
    Route::get('/{id}', [AcademicYearController::class, 'show']);
    Route::put('/{id}', [AcademicYearController::class, 'update']);
    Route::delete('/{id}', [AcademicYearController::class, 'destroy']);
});



// =====================
// Cycles (protégées)
// =====================
Route::middleware('auth:api')->prefix('cycles')->group(function () {
    Route::get('/', [CycleController::class, 'index']);
    Route::post('/', [CycleController::class, 'store']);
    Route::get('/{id}', [CycleController::class, 'show']);
    Route::put('/{id}', [CycleController::class, 'update']);
    Route::delete('/{id}', [CycleController::class, 'destroy']);
});
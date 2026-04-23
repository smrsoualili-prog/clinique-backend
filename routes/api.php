<<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\MedicalRecordController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\HospitalizationController;
use App\Http\Controllers\Api\PrescriptionController;
use App\Http\Controllers\Api\InvoiceController;
use Illuminate\Support\Facades\Route;

// Public
Route::post('/login', [AuthController::class, 'login']);

// Authentifié
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Patients
    Route::middleware('role:admin,medecin,infirmier,reception')->group(function () {
        Route::apiResource('patients', PatientController::class);
        Route::get('patients/{patient}/historique', [PatientController::class, 'historique']);
    });

    // RDV
    Route::middleware('role:admin,medecin,reception')->group(function () {
        Route::apiResource('appointments', AppointmentController::class);
    });

    // Dossiers médicaux
    Route::middleware('role:admin,medecin,infirmier')->group(function () {
        Route::apiResource('medical-records', MedicalRecordController::class);
    });

    // Chambres et lits
    Route::middleware('role:admin,reception')->group(function () {
        Route::apiResource('rooms', RoomController::class);
    });

    // Hospitalisations
    Route::middleware('role:admin,medecin,reception')->group(function () {
        Route::apiResource('hospitalizations', HospitalizationController::class);
    });

    // Prescriptions
    Route::middleware('role:admin,medecin,infirmier')->group(function () {
        Route::apiResource('prescriptions', PrescriptionController::class);
    });

    // Factures
    Route::middleware('role:admin,reception')->group(function () {
        Route::apiResource('invoices', InvoiceController::class);
    });

    // Services
    Route::get('services', [ServiceController::class, 'index']);
    Route::get('services/{service}', [ServiceController::class, 'show']);

    // Admin seulement
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('services', ServiceController::class)->except(['index', 'show']);
        Route::apiResource('users', UserController::class);
    });
});

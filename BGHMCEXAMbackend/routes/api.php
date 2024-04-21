<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientDetailsController;
use App\Http\Controllers\PatientAdmissionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('/all-patients', [PatientDetailsController::class, 'index']);
Route::post('/save-patient', [PatientDetailsController::class, 'save']);
Route::delete('/delete-patient', [PatientDetailsController::class, 'delete']);
Route::put('/update-patient', [PatientDetailsController::class, 'update']);
Route::get('/admitted-patients', [PatientAdmissionController::class, 'index']);
Route::post('/admit-patient', [PatientAdmissionController::class, 'save']);
Route::put('/discharge-patient', [PatientAdmissionController::class, 'updatedischarge']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

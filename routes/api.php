<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EnumeratorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/admin/add-enumerator', [AdminController::class, 'addEnumerator']);
    Route::get('/admin/get-enumerator', [AdminController::class, 'getEnumerator']);
    Route::get('/admin/get-enumerator-information', [AdminController::class, 'getEnumeratorInformation']);
    Route::post('/admin/update-enumerator-status', [AdminController::class, 'updateEnumeratorStatus']);
    Route::post('/admin/create-survey', [AdminController::class, 'createSurvey']);
    Route::get('/admin/get-survey', [AdminController::class, 'getSurvey']);
    Route::get('/admin/get-survey-questionnaire', [AdminController::class, 'getSurveyQuestionnaire']);
    Route::get('/admin/get-survey-response', [AdminController::class, 'getSurveyResponse']);
    Route::get('/admin/get-assign-enumerator', [AdminController::class, 'getAssignEnumerator']);
    Route::get('/admin/get-assign-enumerator-survey', [AdminController::class, 'getAssignEnumeratorSurvey']);
    Route::post('/admin/assign-enumerator', [AdminController::class, 'assignEnumerator']);

    Route::get('/enumerator/get-survey', [EnumeratorController::class, 'getSurvey']);
    Route::post('/enumerator/submit-survey', [EnumeratorController::class, 'submitSurvey']);
    Route::get('/enumerator/get-survey-response', [EnumeratorController::class, 'getSurveyResponse']);

});

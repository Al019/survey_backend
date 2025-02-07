<?php

use App\Http\Controllers\EnumeratorController;
use App\Http\Controllers\SurveyController;
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

    Route::post('/survey/create-survey', [SurveyController::class, 'createSurvey']);
    Route::get('/survey/get-survey', [SurveyController::class, 'getSurvey']);
    Route::get('/survey/get-survey-questionnaire', [SurveyController::class, 'getSurveyQuestionnaire']);
    Route::get('/survey/get-response', [SurveyController::class, 'getResponse']);

    Route::post('/enumerator/add-enumerator', [EnumeratorController::class, 'addEnumerator']);
    Route::get('/enumerator/get-enumerator', [EnumeratorController::class, 'getEnumerator']);
    Route::get('/enumerator/get-enumerator-information', [EnumeratorController::class, 'getEnumeratorInfo']);
    Route::post('/enumerator/update-enumerator-status', [EnumeratorController::class, 'updateEnumeratorStatus']);
    Route::post('/enumerator/submit-survey', [EnumeratorController::class, 'submitSurvey']);
    Route::get('/enumerator/get-response', [EnumeratorController::class, 'getResponse']);

});

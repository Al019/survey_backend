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

    Route::post('/enumerator/add-enumerator', [EnumeratorController::class, 'addEnumerator']);
    Route::get('/enumerator/get-enumerator', [EnumeratorController::class, 'getEnumerator']);
    Route::get('/enumerator/get-survey', [EnumeratorController::class, 'getSurvey']);
    Route::get('/enumerator/get-survey-questionnaire', [EnumeratorController::class, 'getSurveyQuestionnaire']);
    Route::post('/enumerator/submit-survey', [EnumeratorController::class, 'submitSurvey']);

});

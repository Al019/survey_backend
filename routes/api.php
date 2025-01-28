<?php

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

    Route::post('/create-survey', [SurveyController::class, 'createSurvey']);
    Route::get('/get-header', [SurveyController::class, 'getHeader']);
    Route::post('/edit-header', [SurveyController::class, 'editHeader']);
    Route::get('/get-question', [SurveyController::class, 'getQuestion']);
    Route::post('/add-question', [SurveyController::class, 'addQuestion']);
    Route::post('/edit-question', [SurveyController::class, 'editQuestion']);
    Route::post('/delete-question', [SurveyController::class, 'deleteQuestion']);
    Route::post('/delete-option', [SurveyController::class, 'deleteOption']);

});

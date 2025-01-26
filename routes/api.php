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
    Route::get('/get-form', [SurveyController::class, 'getForm']);
    Route::post('/edit-form', [SurveyController::class, 'editForm']);

});

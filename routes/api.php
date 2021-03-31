<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::get('/labels',[\App\Http\Controllers\Api\LabelController::class,'index']);
Route::get('/projects',[\App\Http\Controllers\Api\ProjectController::class,'index']);
Route::apiResource('label',\App\Http\Controllers\Api\LabelController::class);


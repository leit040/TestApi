<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

//Route::get('/labels',[\App\Http\Controllers\Api\LabelController::class,'index']);

Route::middleware('auth:sanctum')->get('/projects',[\App\Http\Controllers\Api\ProjectController::class,'index']);
Route::middleware('auth:sanctum')->put('/projects',[\App\Http\Controllers\Api\ProjectController::class,'update']);
Route::middleware('auth:sanctum')->delete('/projects',[\App\Http\Controllers\Api\ProjectController::class,'destroy']);
Route::middleware('auth:sanctum')->post('/projects',[\App\Http\Controllers\Api\ProjectController::class,'store']);
Route::middleware('auth:sanctum')->post('/projects-link-users',[\App\Http\Controllers\Api\ProjectController::class,'linkToUsers']);


Route::middleware('auth:sanctum')->get('/labels',[\App\Http\Controllers\Api\LabelController::class,'index']);
Route::middleware('auth:sanctum')->put('/labels',[\App\Http\Controllers\Api\LabelController::class,'update']);
Route::middleware('auth:sanctum')->delete('/labels',[\App\Http\Controllers\Api\LabelController::class,'destroy']);
Route::middleware('auth:sanctum')->post('/labels',[\App\Http\Controllers\Api\LabelController::class,'store']);
Route::middleware('auth:sanctum')->post('/labels-link-projects',[\App\Http\Controllers\Api\LabelController::class,'linkToProjects']);


Route::put('/users',[\App\Http\Controllers\Api\UserController::class,'update']);
Route::delete('/users',[\App\Http\Controllers\Api\UserController::class,'destroy']);
Route::post('/users',[\App\Http\Controllers\Api\UserController::class,'store']);
Route::get("/user/{user}/verify",[\App\Http\Controllers\Api\UserController::class,'verify']);
Route::get('/users',[\App\Http\Controllers\Api\UserController::class,'index']);
Route::post('/users/token/create',[\App\Http\Controllers\Api\UserController::class,'authUser']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

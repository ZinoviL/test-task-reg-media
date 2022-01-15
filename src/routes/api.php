<?php

use App\Http\Controllers\DocumentController;
use Illuminate\Http\Request;

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

Route::get('/document', [DocumentController::class, 'list']);
Route::get('/document/{id}', [DocumentController::class, 'show']);
Route::post('/document', [DocumentController::class, 'create']);
Route::patch('/document/{id}', [DocumentController::class, 'edit']);
Route::post('/document/{id}/publish', [DocumentController::class, 'publish']);

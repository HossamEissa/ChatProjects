<?php

use App\Http\Controllers\AssisstantController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ThreadController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/upload/file', [ChatController::class, 'uploadToChatgpt']);
Route::post('/create/assistant', [AssisstantController::class, 'createAssistant']);
Route::post('/create/threads', [ThreadController::class, 'createThreads']);
Route::post('/run/threads', [ThreadController::class, 'runThread']);
Route::post('/get/result', [ThreadController::class, 'interactWithAssistant']);
Route::post('/run', [ThreadController::class, 'outPut']);

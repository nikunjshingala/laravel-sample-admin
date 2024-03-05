<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\API\ProfileController;

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
Route::post('login',[LoginController::class,'loginUser']);
Route::post('/forgot-password', [LoginController::class,'forgotPassword']);
Route::post('/reset-password', [LoginController::class,'resetPassword']);
//add this middleware to ensure that every request is authenticated
Route::group(['middleware' => ['web','auth:api']], function(){
    Route::post('/logout',[LoginController::class,'logoutUser']);
    Route::get('/get-author', [AuthorController::class,'getAuthorList']);
    Route::get('/get-profile', [ProfileController::class,'getProfile']);
    Route::post('/change-password', [ProfileController::class,'changePassword']);
    Route::post('/update-profile', [ProfileController::class,'updateProfile']);
    Route::post('/save-update-device', [ProfileController::class,'saveUpdateDevice']);
});
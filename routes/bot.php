<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Boot\BootController;
use App\Http\Controllers\Boot\MyBotController;
use App\Http\Controllers\Boot\AdminUserController;
use App\Http\Controllers\Boot\TikersUserController;
use App\Http\Controllers\Deposits\DepositsController;
use App\Http\Controllers\Boot\ActiveUserBotController;




// All Mybot

Route::get('AllBot',[BootController::class,'AllBot']);
Route::get('oneBot',[BootController::class,'oneBot']);
Route::get('myBots',[MyBotController::class,'AllMyBot']);
Route::post('storeMyBot',[BootController::class,'storeMyBot']);






// Active Bot and Stop it
Route::post('/activeBot',[ActiveUserBotController::class,'ActiveBot']);
Route::post('/stopBot',[ActiveUserBotController::class,'stopBot']);

// Tickers
Route::get('/allTikers',[TikersUserController::class,'getAllTikers']);
Route::get('/unsubscribeTickers',[TikersUserController::class,'getAllUnsubscrib']);

// All Admin
Route::get('getMyAdmin',[AdminUserController::class,'getMyAdmin']);
Route::get('getAllAdmin',[AdminUserController::class,'getAllAdminAndMyAdmin']);
Route::post('setAdmin',[AdminUserController::class,'setAdmin']);




// for deposite




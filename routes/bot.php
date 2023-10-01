<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Boot\BootController;
use App\Http\Controllers\Boot\MyBotController;
use App\Http\Controllers\Boot\fessBotController;
use App\Http\Controllers\Boot\AdminUserController;
use App\Http\Controllers\Boot\TikersUserController;
use App\Http\Controllers\Deposits\DepositsController;
use App\Http\Controllers\Boot\ActiveUserBotController;




// All Mybot

Route::get('AllBot',[BootController::class,'AllBot']);
Route::get('oneBot',[BootController::class,'oneBot']);
Route::get('myBots',[MyBotController::class,'AllMyBot']);
Route::post('storeMyBot',[MyBotController::class,'storeMyBot']);
Route::post('historyMyBot',[MyBotController::class,'historyMyBot']);
Route::post('shutdown',[MyBotController::class,'shutdownBot']);

// Active Bot and Stop it
Route::post('/activeBot',[ActiveUserBotController::class,'ActiveBot']);
Route::post('/stopBot',[ActiveUserBotController::class,'stopBot']);

// Tickers
Route::get('/allTikers',[TikersUserController::class,'getAllTikers']);
Route::get('/unsubscribeTickers',[TikersUserController::class,'getAllUnsubscrib']);

// All Admin
 Route::get('getAllAdmin',[AdminUserController::class,'getAllAdminAndMyAdmin']);
Route::post('setAdmin',[AdminUserController::class,'setAdmin']);


// for fess from user becouse bot

Route::post('fessBot',[fessBotController::class, 'fees']);





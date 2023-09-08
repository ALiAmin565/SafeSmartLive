<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Boot\AdminUserController;
use App\Http\Controllers\Boot\TikersUserController;
use App\Http\Controllers\Deposits\DepositsController;
use App\Http\Controllers\Boot\ActiveUserBotController;




// Active Bot and Stop it
Route::post('/activeBot',[ActiveUserBotController::class,'ActiveBot']);
Route::post('/stopBot',[ActiveUserBotController::class,'stopBot']);

// Tickers
Route::get('/allTikers',[TikersUserController::class,'getAllTikers']);
Route::get('/unsubscribeTickers',[TikersUserController::class,'getAllUnsubscrib']);

// All Admin
Route::get('getMyAdmin',[AdminUserController::class,'getMyAdmin']);


// for deposite
Route::get('getDeposits',[DepositsController::class,'getDeposits']);



Route::post('sendMony',[TransactionUserController::class,'oneToOne']);
Route::post('mySelf',[TransactionUserController::class,'mySelf']);
Route::post('historyTransaction',[TransactionUserController::class,'historyTransaction']);

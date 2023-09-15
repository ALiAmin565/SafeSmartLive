<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Deposits\WithdrwController;
use App\Http\Controllers\Deposits\DepositsController;
use App\Http\Controllers\Deposits\DepositsUserController;
use App\Http\Controllers\TransactionUser\TransactionUserController;


Route::post('sendMony',[TransactionUserController::class,'oneToOne']);
Route::post('mySelf',[TransactionUserController::class,'mySelf']);
Route::get('historyTransaction',[TransactionUserController::class,'historyTransaction']);


Route::get('getDeposits',[DepositsController::class,'getDeposits']);
Route::post('Withdrw',[WithdrwController::class,'withdraw']);
Route::get('getUSDTBalance',[WithdrwController::class,'getUSDTBalance']);




// Deopsite for user
Route::POST('checkTextID',[DepositsUserController::class,'cheakTextID']);
Route::POST('historyDeposit',[DepositsUserController::class,'historyDeposit']);



<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Deposits\DepositsController;
use App\Http\Controllers\TransactionUser\TransactionUserController;


Route::post('sendMony',[TransactionUserController::class,'oneToOne']);
Route::post('mySelf',[TransactionUserController::class,'mySelf']);
Route::post('historyTransaction',[TransactionUserController::class,'historyTransaction']);


Route::get('getDeposits',[DepositsController::class,'getDeposits']);




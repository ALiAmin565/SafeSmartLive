<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Binance\buyController;



Route::post('binance', [buyController::class, 'buy']);
Route::get('getAllOrders', [buyController::class, 'getAllOrder']);
Route::get('statusOrder', [buyController::class, 'getStatusOrder']);
Route::get('canselOrder', [buyController::class, 'canselOrder']);
Route::get('timestampBinance', [buyController::class, 'timestampBinance']);



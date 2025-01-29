<?php

use App\Http\Controllers\Api\V1\ClientsController;
use App\Http\Controllers\Api\V1\TransactionsController;
use App\Http\Controllers\Api\V1\ExchangeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::prefix('v1')->group(function(){
    Route::apiResource('/clients', ClientsController::class);
    Route::get('/clients/{id}/rewards', [ClientsController::class, 'getRewards']);
    Route::post('/transactions', [TransactionsController::class, 'store']);
    Route::post('/exchange', [ExchangeController::class, 'store']);
});
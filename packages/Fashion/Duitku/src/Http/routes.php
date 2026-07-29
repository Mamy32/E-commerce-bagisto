<?php

use Illuminate\Support\Facades\Route;
use Fashion\Duitku\Http\Controllers\PaymentController;

Route::group(['middleware' => ['web']], function () {
    Route::get('/duitku/redirect', [PaymentController::class, 'redirect'])->name('duitku.redirect');
    Route::get('/duitku/success', [PaymentController::class, 'success'])->name('duitku.success');
});

Route::group(['middleware' => ['api']], function () {
    Route::post('/webhook/duitku', [PaymentController::class, 'webhook'])->name('duitku.webhook');
});

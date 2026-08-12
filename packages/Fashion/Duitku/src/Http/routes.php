<?php

use Illuminate\Support\Facades\Route;
use Fashion\Duitku\Http\Controllers\PaymentController;

Route::group(['middleware' => ['web']], function () {
    Route::get('/duitku/redirect', [PaymentController::class, 'redirect'])->name('duitku.redirect');
    Route::get('/duitku/success', [PaymentController::class, 'success'])->name('duitku.success');
    
    // API endpoints for Vue checkout component
    Route::get('/duitku/payment-methods', [PaymentController::class, 'getPaymentMethods'])->name('duitku.payment_methods');
    Route::post('/duitku/set-method', [PaymentController::class, 'setMethod'])->name('duitku.set_method');
    Route::get('/duitku/pay/{id}', [PaymentController::class, 'payNow'])->name('duitku.pay_now');
});

Route::group(['middleware' => ['api']], function () {
    Route::post('/webhook/duitku', [PaymentController::class, 'webhook'])->name('duitku.webhook');
});

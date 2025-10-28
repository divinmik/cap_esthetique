<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/callback', function (Request $request) {
     Log::info('Mobile Money - callback ok', [
                'data' => $request->all(),
            ]);
        return response()->json(200);
    });

Route::prefix('payments')->group(function () {

    

    // Initier un paiement
    Route::post('/initiate', [PaymentController::class, 'initiatePayment']);

    // Vérifier le statut d'un paiement
    Route::post('/check-status', [PaymentController::class, 'checkPaymentStatus']);

    // Recevoir les callbacks (pas de middleware auth)
    Route::post('/callback', [PaymentController::class, 'handleCallback']);
});

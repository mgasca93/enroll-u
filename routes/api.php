<?php

use App\Http\Controllers\CustomersController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaymentsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [LoginController::class, 'login']);



Route::middleware('auth:sanctum')->get('/logout', function (Request $request) {
    $user = $request->user();
    // Revoke all tokens...
    $user->tokens()->delete();
    // Revoke the token that was used to authenticate the current request...
    $request->user()->currentAccessToken()->delete();

    return response()->json(['message' => 'User logout with success'], 200);
});

Route::middleware(['auth:sanctum'])->group(function(){

    Route::post('customers', [CustomersController::class, 'registerCustomer']);
    Route::get('customers', [CustomersController::class, 'showCustomersAPI']);
    Route::get('customers/{id}', [CustomersController::class, 'showCustomerById']);
    Route::delete('customers/{id}', [CustomersController::class, 'deleteCustomer']);
    Route::put('customers/{id}', [CustomersController::class, 'updateCustomer']);

    Route::post('payments/{id}', [PaymentsController::class, 'registerPayment']);
    Route::get('payments', [PaymentsController::class, 'showPaymentsAPI']);
    Route::get('payments/{id}', [PaymentsController::class, 'showPaymentsByCustomerId']);
    Route::delete('payments/{id}', [PaymentsController::class, 'deletePayment']);
    Route::put('payments/{id}', [PaymentsController::class, 'updatePayment']);

});
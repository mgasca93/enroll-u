<?php

use App\Http\Controllers\CustomersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PaymentsController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware([RedirectIfAuthenticated::class])->group( function(){
    Route::get('/', [PagesController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'index'])->name('login.validate');
});

Route::middleware([Authenticate::class])->group(function(){
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::post('/logout',[LoginController::class, 'logout'])->name('logout');
    Route::get('/customers', [CustomersController::class, 'index'])->name('customers.view');
    Route::post('/customers', [CustomersController::class, 'store'])->name('customers.add');
    Route::delete('/customers', [CustomersController::class, 'delete'])->name('customers.delete');
    Route::get('/customers/{id}', [CustomersController::class, 'getCustomer'])->name('customers.get');
    Route::post('/customers-update', [CustomersController::class, 'update'])->name('customers.update');

    Route::get('/payments',[PaymentsController::class, 'index'])->name('payments.view');
    Route::post('/payments', [PaymentsController::class, 'store'])->name('payments.add');
    Route::delete('/payments', [PaymentsController::class, 'delete'])->name('payments.delete');
    Route::get('/payments/{id}', [PaymentsController::class, 'getPayment'])->name('payments.get');
    Route::post('/payments-update', [PaymentsController::class, 'update'])->name('payments.update');
});


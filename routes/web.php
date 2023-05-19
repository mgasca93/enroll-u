<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PagesController;
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
});


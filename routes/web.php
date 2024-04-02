<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Auth::routes();

Route::group(['prefix' => 'dashboard',  'middleware' => 'auth'], function(){
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');
});

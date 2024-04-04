<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    RoleAndPermissionController
};

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Auth::routes();

Route::group(['prefix' => 'dashboard',  'middleware' => 'auth'], function(){
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/roles-and-permissions', RoleAndPermissionController::class);
});

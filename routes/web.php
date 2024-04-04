<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    UserController,
    RoleAndPermissionController
};

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Auth::routes();

Route::group(['prefix' => 'dashboard',  'middleware' => 'auth'], function(){
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resources([
        '/users' => UserController::class,
        '/roles-and-permissions' => RoleAndPermissionController::class
    ]);
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    CompanyController,
    DashboardController,
    LocationController,
    NoteController,
    PaymentController,
    ReportController,
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
        'companies' => CompanyController::class,
        'locations' => LocationController::class,
        'notes' => NoteController::class,
        'payments' => PaymentController::class,
        'reports' => ReportController::class,
        'roles-and-permissions' => RoleAndPermissionController::class,
        'users' => UserController::class,
    ]);
});

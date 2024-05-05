<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    CompanyController,
    DashboardController,
    FileController,
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

Auth::routes(['register' => false, 'reset' => true]);

Route::group(['prefix' => 'dashboard',  'middleware' => 'auth'], function(){
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resources([
        'companies' => CompanyController::class,
        'files' => FileController::class,
        'locations' => LocationController::class,
        'notes' => NoteController::class,
        'payments' => PaymentController::class,
        'reports' => ReportController::class,
        'roles-and-permissions' => RoleAndPermissionController::class,
        'users' => UserController::class,
    ]);
    Route::post('/payments/fill', [PaymentController::class, 'fill'])->name('payments.fill');
});

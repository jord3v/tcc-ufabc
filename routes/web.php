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
    
    Route::get('/payments/{payment:uuid}', [PaymentController::class, 'show'])->name('payments.show');
    Route::put('/payments/{payment:uuid}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/payments/{payment:uuid}', [PaymentController::class, 'destroy'])->name('payments.destroy');

    Route::post('/payments/fill', [PaymentController::class, 'fill'])->name('payments.fill');
    Route::get('/payments/download/{zipname}', [PaymentController::class, 'download'])->name('payments.download');

});

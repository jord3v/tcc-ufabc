<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    CompanyController,
    DashboardController,
    FileController,
    LocationController,
    NoteController,
    PaymentController,
    ProtocolController,
    ReportController,
    UserController,
    RoleAndPermissionController
};

Route::get('/', function () {
    return to_route('dashboard');
});

Auth::routes(['register' => false, 'reset' => true]);

Route::group(['prefix' => 'dashboard',  'middleware' => ['auth', 'prevent-demo-actions']], function(){
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports/list', [ReportController::class, 'list'])->name('reports.list');
    Route::post('/reports/download', [ReportController::class, 'download'])->name('reports.download');
    Route::resources([
        'companies' => CompanyController::class,
        'files' => FileController::class,
        'locations' => LocationController::class,
        'notes' => NoteController::class,
        'payments' => PaymentController::class,
        'protocols' => ProtocolController::class,
        'reports' => ReportController::class,
        'roles-and-permissions' => RoleAndPermissionController::class,
        'users' => UserController::class,
    ]);
    Route::get('/payments/{payment:uuid}', [PaymentController::class, 'show'])->name('payments.show');
    Route::put('/payments/{payment:uuid}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/payments/{payment:uuid}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    Route::post('/payments/fill', [PaymentController::class, 'fill'])->name('payments.fill');
    Route::get('/payments/download/{zipname}', [PaymentController::class, 'download'])->name('payments.download');
    Route::post('/payments/last', [PaymentController::class, 'last'])->name('payments.last');
    Route::put('/update-profile', [UserController::class, 'updateProfile'])->name('users.update-profile'); 
    Route::post('protocols/attachment', [ProtocolController::class, 'attachment'])->name('protocols.attachment');
});


Route::get('/test', function () {
    $note = App\Models\Note::find(2);
    $diferenciaMeses = $note->start->diffInMonths($note->end);
    return $diferenciaMeses;

    //return 'foo';
});
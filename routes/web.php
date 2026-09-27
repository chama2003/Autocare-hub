<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\JobCardController;
use App\Http\Controllers\SparePartController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AutoCare Hub Web Routes
|--------------------------------------------------------------------------
| Assumes an auth scaffold (Laravel Breeze) providing login/register/logout
| and the 'auth' + 'verified' middleware group. See README for setup.
*/

Route::get('/', fn () => redirect()->route('login'))->name('home');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ---- Vehicles (customers manage their own; staff/admin manage all) ----
    Route::resource('vehicles', VehicleController::class);

    // ---- Appointment booking (all authenticated roles) ----
    Route::get('/appointments/calendar-feed', [AppointmentController::class, 'calendarFeed'])->name('appointments.feed');
    Route::resource('appointments', AppointmentController::class)->only(['index', 'create', 'store']);

    Route::middleware('role:staff,admin')->group(function () {
        Route::post('/appointments/{appointment}/approve', [AppointmentController::class, 'approve'])->name('appointments.approve');
        Route::put('/appointments/{appointment}/reschedule', [AppointmentController::class, 'reschedule'])->name('appointments.reschedule');
        Route::post('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
    });

    // ---- Job cards (mechanic/workshop panel) ----
    Route::middleware('role:staff,admin,mechanic')->group(function () {
        Route::resource('jobcards', JobCardController::class)->only(['index', 'create', 'store', 'show']);
        Route::put('/jobcards/{jobCard}/status', [JobCardController::class, 'updateStatus'])->name('jobcards.status');
        Route::post('/jobcards/{jobCard}/services', [JobCardController::class, 'addServiceItem'])->name('jobcards.services.add');
        Route::post('/jobcards/{jobCard}/parts', [JobCardController::class, 'addPart'])->name('jobcards.parts.add');
    });

    // ---- Invoicing ----
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');

    Route::middleware('role:staff,admin')->group(function () {
        Route::post('/jobcards/{jobCard}/invoice', [InvoiceController::class, 'generate'])->name('invoices.generate');
        Route::post('/invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('invoices.markPaid');
    });

    // ---- Inventory & spare parts (staff/admin only) ----
    Route::middleware('role:staff,admin')->group(function () {
        Route::resource('inventory', SparePartController::class)
            ->parameters(['inventory' => 'sparePart'])
            ->except(['show']);
        Route::post('/inventory/{sparePart}/restock', [SparePartController::class, 'restock'])->name('inventory.restock');
    });

    // ---- Revenue & expense dashboard (admin only) ----
    Route::middleware('role:admin')->group(function () {
        Route::get('/finance/dashboard', [FinanceController::class, 'dashboard'])->name('finance.dashboard');
        Route::get('/finance/expenses', [FinanceController::class, 'expenses'])->name('finance.expenses');
        Route::post('/finance/expenses', [FinanceController::class, 'storeExpense'])->name('finance.expenses.store');
    });
});

// require __DIR__.'/auth.php'; // Provided by Laravel Breeze - see README

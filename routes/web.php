<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Revolution\Google\Sheets\Facades\Sheets;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SalesOrderUIController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PurchaseOrderUIController;
use App\Http\Controllers\SupplierController;



// Root yönlendirme → Dashboard
Route::redirect('/', '/dashboard')->middleware(['auth', 'verified']);

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


/* ==============================
   Profil
============================== */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


/* ==============================
   Google Login
============================== */
Route::get('/auth/google', fn() => Socialite::driver('google')->redirect())
    ->name('google.login');

Route::get('/auth/google/callback', function () {
    $googleUser = Socialite::driver('google')->user();

    $user = \App\Models\User::firstOrCreate(
        ['email' => $googleUser->getEmail()],
        [
            'name' => $googleUser->getName(),
            'password' => bcrypt(str()->random(16)),
        ]
    );

    Auth::login($user);
    return redirect('/dashboard');
});


/* ==============================
   ERP ROUTES
============================== */
Route::middleware(['auth', 'verified'])->group(function () {

    // Ürün Yönetimi
    Route::resource('products', ProductController::class);

    // Kategori Yönetimi
    Route::resource('categories', ProductCategoryController::class);

    // Müşteriler
    Route::resource('customers', CustomerController::class);

    // CUSTOMER NOTES (USP)
    Route::post('/customers/{customer}/notes', [CustomerController::class, 'addNote'])
        ->name('customers.notes.store');

    Route::patch('/customers/notes/{note}', [CustomerController::class, 'updateNote'])
        ->name('customers.notes.update');

    Route::delete('/customers/notes/{note}', [CustomerController::class, 'deleteNote'])
        ->name('customers.notes.delete');


    // Stok Yönetimi
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::post('/stock/{id}/update', [StockController::class, 'updateStock'])->name('stock.update');
    Route::patch('/stock/{id}/update-min-level', [StockController::class, 'updateMinLevel'])->name('stock.updateMin');

    // Bildirimler
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');


    Route::middleware('auth')->group(function () {

        Route::get('/sales', [SalesOrderUIController::class, 'index'])->name('sales.index');

        Route::get('/sales/create', [SalesOrderUIController::class, 'create'])->name('sales.create');

        Route::post('/sales/store', [SalesOrderUIController::class, 'store'])->name('sales.store');

        Route::get('/sales/{order}', [SalesOrderUIController::class, 'show'])->name('sales.show');

        Route::put('/sales/{order}', [SalesOrderUIController::class, 'update'])->name('sales.update');

        Route::delete('/sales/{order}', [SalesOrderUIController::class, 'destroy'])->name('sales.destroy');
    });

    Route::middleware('auth')->group(function () {

        Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
        Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');
        Route::get('/invoices/{invoice}/pdf/view', [InvoiceController::class, 'viewPdf'])->name('invoices.pdf.view');


        Route::post('/sales/{order}/invoice', [InvoiceController::class, 'createFromOrder'])
            ->name('sales.invoice.create');

    });

    Route::middleware('auth')->group(function () {

        Route::get('/purchase', [PurchaseOrderUIController::class, 'index'])->name('purchase.index');
        Route::get('/purchase/create', [PurchaseOrderUIController::class, 'create'])->name('purchase.create');
        Route::post('/purchase/store', [PurchaseOrderUIController::class, 'store'])->name('purchase.store');

        Route::get('/purchase/{order}', [PurchaseOrderUIController::class, 'show'])->name('purchase.show');
        Route::put('/purchase/{order}', [PurchaseOrderUIController::class, 'update'])->name('purchase.update');
        Route::delete('/purchase/{order}', [PurchaseOrderUIController::class, 'destroy'])->name('purchase.destroy');
    });

    Route::middleware('auth')->group(function () {

        Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])
            ->name('suppliers.show');


    });



});


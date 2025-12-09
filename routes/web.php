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
        Route::put('/sales/{order}', [SalesOrderUIController::class, 'update'])->name('sales.update'); // 🔥 EKLENDİ
    });


});


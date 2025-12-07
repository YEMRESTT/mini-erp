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

// Root → Dashboard yönlendirmesi
Route::get('/', function () {
    return redirect()->route('dashboard');
})->middleware(['auth', 'verified']);

// Dashboard Controller route
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Google Auth Routes
Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('google.login');

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

// Sheets Test Route
Route::get('/sheets-test', function () {
    $credentialsPath = storage_path('app\mini-erp-479413-7a8dbcbc7e35.json');

    if (!File::exists($credentialsPath)) {
        return 'HATA: mini-erp-479413-7a8dbcbc7e35.json dosyası bulunamıyor.';
    }

    $credentialsContent = File::get($credentialsPath);

    try {
        $client = new Google\Client();
        $client->setAuthConfig(json_decode($credentialsContent, true));
        $client->setScopes([\Google\Service\Sheets::SPREADSHEETS]);
        putenv("GOOGLE_APPLICATION_CREDENTIALS=$credentialsPath");
    } catch (\Exception $e) {
        return 'Google Client hatası: ' . $e->getMessage();
    }

    $sheetId = env('GOOGLE_SHEET_ID');

    Sheets::spreadsheet($sheetId)
        ->sheet('Sheet1')
        ->append([
            ['ID', 'Name', 'Email'],
            [1, 'Yusuf', 'test@example.com']
        ]);

    return 'Google Sheets bağlantısı çalıştı! 🚀';
});

// Product Routes
Route::resource('products', ProductController::class);

// Category Routes
Route::resource('categories', ProductCategoryController::class);

// Stock Routes
Route::middleware('auth')->group(function () {
    Route::get('stock', [StockController::class, 'index'])->name('stock.index');
    Route::post('stock/{id}/update', [StockController::class, 'updateStock'])->name('stock.update');
    Route::patch('stock/{id}/update-min-level', [StockController::class, 'updateMinLevel'])->name('stock.updateMin');
});

// Notification Routes
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});

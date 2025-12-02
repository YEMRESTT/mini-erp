<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Revolution\Google\Sheets\Facades\Sheets;
use Illuminate\Support\Facades\File;

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('google.login');

Route::get('/auth/google/callback', function () {
    $googleUser = Socialite::driver('google')->user();

    // Kullanıcı var mı kontrol
    $user = \App\Models\User::firstOrCreate(
        ['email' => $googleUser->getEmail()],
        [
            'name' => $googleUser->getName(),
            'password' => bcrypt(str()->random(16)), // email/password giriş de çalışsın diye
        ]
    );

    Auth::login($user);

    return redirect('/dashboard');
});



Route::get('/sheets-test', function () {
    // 1. JSON dosyasının bulunduğu doğru yolu belirleyin.
    // storage_path() kullanmak, C:\xampp\htdocs\... gibi karmaşık yollardan kurtarır.
    $credentialsPath = storage_path('app\mini-erp-479413-7a8dbcbc7e35.json');

    if (!File::exists($credentialsPath)) {
        // Dosya bulunamazsa hata döndür
        return 'HATA: mini-erp-479413-7a8dbcbc7e35.json dosyası bulunamıyor. Lütfen yolu kontrol edin: ' . $credentialsPath;
    }

    // 2. JSON dosyasının içeriğini oku
    $credentialsContent = File::get($credentialsPath);

    // 3. Kütüphanenin yapılandırmasını DİNAMİK olarak ayarla
    // Bu adım, kütüphane için bir "hizmet hesabı" yapılandırması ayarlar.
    // Kullandığınız kütüphanenin API'sine göre bu metot değişebilir.

    // Örnek Kütüphane Yapılandırma Senaryosu (Sizin kütüphanenize uyarlayın)
    // Eğer kütüphane, konfigürasyonu çalışma zamanında ayarlamaya izin veriyorsa:

    // 3.1. Kütüphane metodu ile yapılandırma (Eğer varsa)
    // Sheets::setServiceAccountCredentials($credentialsContent);

    // 3.2. VEYA, Google Client'ı elle başlatıp kütüphaneye vermek (En Garanti Yöntem)

    // Google API Client'ı dahil et (composer ile kurulmuş olmalı)
    // use Google\Client;
    // use Google\Service\Sheets;

    try {
        $client = new Google\Client();

        // Kimlik bilgilerini doğrudan JSON içeriği olarak ver
        $client->setAuthConfig(json_decode($credentialsContent, true));

        // Sheets API için gerekli kapsamı tanımla
        $client->setScopes([\Google\Service\Sheets::SPREADSHEETS]);

        // Kütüphaneye, bu yetkilendirilmiş Google Client nesnesini kullanmasını söyle
        // Bu, kullandığınız kütüphanenin API'sindeki özel bir metot olabilir.
        // Eğer kütüphane doğrudan Sheets::client($client) gibi bir metot sunmuyorsa, bu kısım kütüphanenin nasıl çalıştığına bağlıdır.

        // Eğer kütüphane, konfigürasyon yoluyla çalışıyorsa, bu elle başlatma adımı gerekmeyebilir.


        // Eğer kütüphane, sadece ENV'deki yolu okuyorsa:
        // ENV değişkenini KOD içerisinde anlık olarak ayarla
        putenv("GOOGLE_APPLICATION_CREDENTIALS=$credentialsPath");
        config(['filesystems.disks.google.credentials' => $credentialsPath]); // Bazı kütüphaneler dosya sistemini kullanır

    } catch (\Exception $e) {
        return 'HATA: Google Client başlatılırken bir sorun oluştu. Detay: ' . $e->getMessage();
    }


    $sheetId = env('GOOGLE_SHEET_ID');

    // sheets-test rotası, yetkilendirme adımlarından sonra çalışır
    Sheets::spreadsheet($sheetId)
        ->sheet('Sheet1')
        ->append([
            ['ID', 'Name', 'Email'],
            [1, 'Yusuf', 'test@example.com']
        ]);

    return 'Google Sheets bağlantısı ÇALIŞIYOR! 🚀';
});

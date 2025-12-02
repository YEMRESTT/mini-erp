<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

/*
 * 🟦 user_id

Bu profil hangi kullanıcıya ait?

🟩 phone / address

Kullanıcının iletişim bilgileri.

🟧 photo_url

Kullanıcının profil fotoğrafının yolu.

Google OAuth ile giriş yaptıysa otomatik Google fotoğrafı.

Manuel yüklerse sistemden alınan fotoğraf.

🟪 last_login_at / last_login_ip / last_login_device

Güvenlik ve loglama için çok önemli.

Örn:

Son giriş: 2025-11-25 17:22

IP: 192.168.1.15

Cihaz: Chrome Windows 10

Dashboard’da “Aktif kullanıcılar” ekranında işimize yarayacak.
 */
class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'photo_url',
        'last_login_at',
        'last_login_ip',
        'last_login_device',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

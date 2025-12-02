# Laravel Projesini GitHub'a Yükleme Rehberi

## 1. Git'i Başlat

Proje klasörünüzde terminal açın ve Git'i başlatın:

```bash
git init
```

## 2. .gitignore Dosyası Oluşturun

 Proje kök dizininizde `.gitignore` dosyası yoksa oluşturun ve şu içeriği ekleyin:

```gitignore
/node_modules
/public/hot
/public/storage
/storage/*.key
/vendor
.env
.env.backup
.env.production
.phpunit.result.cache
docker-compose.override.yml
Homestead.json
Homestead.yaml
auth.json
npm-debug.log
yarn-error.log
/.fleet
/.idea
/.vscode
```

## 3. Dosyaları Git'e Ekleyin

```bash
git add .
```

## 4. İlk Commit'i Yapın

```bash
git commit -m "Initial commit"
```

## 5. GitHub'da Repository Oluşturun

1. GitHub'a giriş yapın
2. Sağ üstteki **"+"** işaretine tıklayın
3. **"New repository"** seçin
4. Repository adını girin
5. **"Create repository"** butonuna tıklayın

## 6. Remote Repository'yi Bağlayın

GitHub'da oluşturduğunuz repository'nin URL'sini kullanarak:

```bash
git remote add origin https://github.com/YEMRESTT/repository-adi.git
```

> **Not:**  `repository-adi` kısımlarını kendi bilgilerinizle değiştirin.

## 7. Kodu GitHub'a Yükleyin

```bash
git branch -M main
git push -u origin main
```

---

##  GitHub'dan Kod Geri Çekme (Hata Durumunda)

Eğer editörünüz (Tree, VS Code vb.) hata yaparsa veya dosyalarınız bozulursa, GitHub'dan son sağlıklı hali geri çekebilirsiniz:

### Yöntem 1: Tüm Projeyi Sıfırlama (En Güvenli)

```bash
# Mevcut değişiklikleri kaydetmeden sıfırlama
git reset --hard HEAD

# GitHub'dan en son hali çekme
git pull origin main
```

### Yöntem 2: Belirli Dosyaları Geri Yükleme

```bash
# Belirli bir dosyayı geri yükleme
git checkout HEAD -- dosya_adi.php

# Belirli bir klasörü geri yükleme
git checkout HEAD -- app/Models/
```

### Yöntem 3: Son Commit'e Geri Dönme

```bash
# Son commit'e geri dön (çalışma dizinindeki değişiklikleri koru)
git reset --soft HEAD~1

# Son commit'e geri dön (tüm değişiklikleri sil)
git reset --hard HEAD~1
```

### Yöntem 4: Acil Durum - Projeyi Tamamen Yeniden İndirme

```bash
# Mevcut klasörü yedekle
mv proje_adi proje_adi_backup

# GitHub'dan temiz kopya indir
git clone https://github.com/YEMRESTT/repository-adi.git

# Bağımlılıkları yükle
cd repository-adi
composer install
npm install
cp .env.example .env
php artisan key:generate
```

---

###  Uyarı

- `git reset --hard` komutu **geri alınamaz** değişiklikler yapar
- Önemli değişikliklerinizi commit'lemeden önce yedeklemeyi unutmayın
- `.env` dosyanızı her zaman ayrı bir yerde yedekleyin


---

### Sonraki Adımlar

Projenizi başka bir yerde çalıştırmak için:

1. Repository'yi klonlayın:
   ```bash
   git clone https://github.com/YEMRESTT/repository-adi.git
   ```

2. Bağımlılıkları yükleyin:
   ```bash
   composer install
   npm install
   ```

3. `.env` dosyasını oluşturun ve yapılandırın:
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```


```bash
    git add .
    git commit -m "Initial commit"
    git push
```

```bash
    git add .
```
```bash
    git commit -m "Initial commit"
```

```bash
    git push
```

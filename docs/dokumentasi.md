# Dokumentasi Project Gymflow

## Deskripsi Project

Gymflow adalah sebuah aplikasi berbasis web yang dirancang untuk membantu manajemen dan pelacakan aktivitas gym. Aplikasi ini menyediakan fitur untuk mengelola jadwal latihan, mencatat kemajuan latihan, mengelola data anggota, serta menyediakan antarmuka yang mudah digunakan baik untuk pelatih maupun pengguna gym.

## Teknologi yang Digunakan

- **Laravel** - Framework PHP untuk backend
- **Composer** - Dependency manager untuk PHP
- **NPM** - Package manager untuk JavaScript
- **Webpack** - Module bundler untuk asset management
- **PHP** - Bahasa pemrograman utama
- **MySQL/PostgreSQL** - Database (tergantung konfigurasi)

## Struktur Folder

```
Gymflow/
├── .editorconfig           # Konfigurasi format penulisan kode
├── .env.example           # Contoh file environment
├── .gitattributes         # Konfigurasi Git
├── .gitignore             # File yang diabaikan oleh Git
├── artisan                # CLI Laravel
├── composer.json          # Dependency PHP
├── composer.lock          # Versi dependency PHP terkunci
├── package.json           # Dependency JavaScript
├── phpunit.xml            # Konfigurasi pengujian
├── README.md              # Dokumentasi awal
├── server.php             # File server PHP
├── webpack.mix.js         # Konfigurasi Webpack
├── .factory/              # Factory untuk testing
├── app/                   # Kode aplikasi utama
├── bootstrap/             # File bootstrapping aplikasi
├── config/                # Konfigurasi aplikasi
├── database/              # Migration, seed, dan model database
├── public/                # File publik dan asset
└── docs/                  # Dokumentasi (folder baru)
```

## Instalasi

### Prasyarat
- PHP >= 8.0
- Composer
- Node.js dan NPM
- Database (MySQL/PostgreSQL/SQLite)

### Langkah Instalasi

1. **Clone repository**
```bash
git clone [URL_REPOSITORY]
cd Gymflow
```

2. **Install dependency PHP**
```bash
composer install
```

3. **Install dependency JavaScript**
```bash
npm install
```

4. **Copy file environment dan konfigurasi**
```bash
cp .env.example .env
```
Konfigurasikan database dan pengaturan lainnya di file `.env`.

5. **Generate application key**
```bash
php artisan key:generate
```

6. **Migrate database**
```bash
php artisan migrate
```

7. **Jalankan seeding database (jika ada)**
```bash
php artisan db:seed
```

8. **Build asset**
```bash
npm run dev
```
atau untuk production:
```bash
npm run prod
```

9. **Jalankan aplikasi**
```bash
php artisan serve
```
Aplikasi akan berjalan di `http://localhost:8000`

## Konfigurasi Environment

File `.env` berisi konfigurasi penting seperti:

- `APP_NAME` - Nama aplikasi
- `APP_ENV` - Environment (local, production, dll)
- `APP_KEY` - Kunci enkripsi aplikasi
- `DB_*` - Konfigurasi database
- `MAIL_*` - Konfigurasi email
- `SESSION_*` - Konfigurasi sesi

## Fitur Utama

1. **Manajemen User/Anggota**
   - Registrasi dan login
   - Profil anggota
   - Manajemen hak akses

2. **Manajemen Jadwal Latihan**
   - Pembuatan jadwal latihan
   - Pemilihan jenis latihan
   - Penjadwalan sesi

3. **Pelacakan Kemajuan**
   - Pencatatan latihan harian
   - Grafik kemajuan
   - Statistik performa

4. **Manajemen Inventaris**
   - Daftar alat gym
   - Status ketersediaan
   - Pemeliharaan alat

## Struktur Database

Struktur database tidak sepenuhnya terlihat dari struktur folder, namun biasanya akan mencakup tabel-tabel seperti:

- `users` - Data pengguna
- `members` - Data anggota gym
- `workouts` - Data latihan
- `schedules` - Jadwal latihan
- `progress_records` - Catatan kemajuan
- `equipment` - Inventaris alat gym
- `sessions` - Session pengguna

## Testing

Aplikasi menggunakan PHPUnit untuk pengujian. Untuk menjalankan test:

```bash
php artisan test
```

Atau:
```bash
./vendor/bin/phpunit
```

## Deployment

Untuk deployment ke production:

1. Konfigurasi environment production
2. Jalankan migrate
3. Optimasi autoloader: `composer install --optimize-autoloader --no-dev`
4. Optimasi Laravel: `php artisan config:cache`, `php artisan route:cache`, `php artisan view:cache`
5. Build asset production: `npm run prod`

## Kontribusi

1. Fork repository
2. Buat branch fitur (`git checkout -b fitur/NamaFitur`)
3. Commit perubahan (`git commit -m 'Add some NamaFitur'`)
4. Push ke branch (`git push origin fitur/NamaFitur`)
5. Buat Pull Request

## Lisensi

Lisensi spesifik tidak disebutkan dalam struktur awal, silakan cek file lisensi jika ada atau sesuaikan dengan kebijakan organisasi Anda.

## Penulis

Project Gymflow - Tugas Akhir Semester 7 - PWL
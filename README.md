# 🎮 GameHub — Platform Distribusi Game Digital

Project UAS Pemrograman Web Lanjut — Laravel 12

## 📋 Tentang Project

GameHub adalah platform distribusi game digital yang memungkinkan pengguna untuk:
- 🛒 Membeli game secara digital
- 🎁 Menghadiahkan game ke teman (Gift System)
- 📚 Mengelola koleksi game di Library pribadi
- ❤️ Membuat dan berbagi Wishlist
- 💬 Berinteraksi di Community (Forum & Friends)
- ⭐ Memberikan Review dan Rating game

## 🛠️ Tech Stack

- **Framework**: Laravel 12
- **Database**: MySQL
- **Frontend**: Blade Template + Custom CSS (Dark Gaming Theme)
- **Auth**: Laravel Built-in Authentication

## ⚙️ Cara Menjalankan

### Requirements
- PHP >= 8.2
- Composer
- MySQL

### Instalasi

```bash
# 1. Clone repository
git clone https://github.com/USERNAME/gamehub.git
cd gamehub

# 2. Install dependencies
composer install

# 3. Copy file environment
cp .env.example .env

# 4. Generate key
php artisan key:generate

# 5. Konfigurasi database di .env
DB_DATABASE=gamehub
DB_USERNAME=root
DB_PASSWORD=

# 6. Jalankan migration dan seeder
php artisan migrate:fresh --seed

# 7. Storage link
php artisan storage:link

# 8. Jalankan server
php artisan serve
```

Buka browser: `http://127.0.0.1:8000`

## 👤 Akun Demo

| Role  | Email               | Password    |
|-------|---------------------|-------------|
| Admin | admin@gamehub.com   | password123 |
| User  | budi@gamehub.com    | password123 |
| User  | sari@gamehub.com    | password123 |

## ✨ Fitur Lengkap

| Fitur                    | Status |
|--------------------------|--------|
| Register dan Login       | ✅     |
| Store dan Filter Game    | ✅     |
| Cart dan Checkout        | ✅     |
| Gift System              | ✅     |
| Library Pribadi          | ✅     |
| Wishlist Publik/Privat   | ✅     |
| Community Forum          | ✅     |
| Friends System           | ✅     |
| Review dan Rating        | ✅     |
| Admin Dashboard          | ✅     |
| Admin CRUD Game          | ✅     |
| Admin Kelola User        | ✅     |
| Admin Kelola Order       | ✅     |

---
> Dibuat untuk UAS Pemrograman Web Lanjut  
> Universitas Ma'soem — 2025/2026
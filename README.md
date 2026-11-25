# FlipPlay REST API (Demo Modul 4)

File di folder ini adalah file kustom untuk project Laravel 12. Strukturnya sama dengan project Laravel, jadi tinggal di-copy (timpa jika diminta).

## Langkah pemasangan

1. Buat project, lalu masuk ke foldernya:
   ```
   composer create-project laravel/laravel flipplay-api
   cd flipplay-api
   ```
2. Buat database kosong bernama `flipplay` lewat HeidiSQL atau phpMyAdmin, lalu ubah `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=flipplay
   DB_USERNAME=root
   DB_PASSWORD=
   ```
3. Aktifkan routing API (sesuai Modul 4 bagian 1.1):
   ```
   php artisan install:api
   ```
   Jawab `no` jika ditanya soal migrasi tambahan.
4. Copy semua isi folder ini ke project, timpa file yang sudah ada:
   `app/`, `bootstrap/app.php`, `database/`, `routes/api.php`.
5. Jalankan migrasi dan isi data awal:
   ```
   php artisan migrate:fresh --seed
   ```
6. Cek route dan jalankan server:
   ```
   php artisan route:list --path=api
   php artisan serve
   ```
7. Import `FlipPlay_API.postman_collection.json` ke Postman, lalu jalankan request nomor 01 sampai 22 secara berurutan (pakai data hasil `migrate:fresh --seed`).

Semua request di Postman wajib memakai header `Accept: application/json` (sudah ada di collection).

## Upload ke GitHub (ketentuan pertama Demo)

```
git init
git add .
git commit -m "FlipPlay REST API Modul 4"
git branch -M main
git remote add origin https://github.com/USERNAME/NAMA-REPO.git
git push -u origin main
```
File `.env` otomatis tidak ikut ter-upload (sudah ada di `.gitignore`).

## Endpoint

| Method | Endpoint | Fungsi | Status sukses |
|---|---|---|---|
| GET | /api/media | Daftar media (`?search=`, `?type=`, pagination 10) | 200 |
| GET | /api/media/{id} | Detail media beserta pengguna yang mengoleksinya | 200 |
| POST | /api/media | Tambah media | 201 |
| PATCH | /api/media/{id} | Ubah media | 200 |
| DELETE | /api/media/{id} | Hapus media | 200 |
| GET | /api/users | Daftar pengguna | 200 |
| GET | /api/users/{id} | Detail pengguna beserta koleksinya | 200 |
| POST | /api/users/{id}/collection | Tambah media ke koleksi (body: `media_id`) | 201 |
| DELETE | /api/users/{id}/collection/{media} | Hapus media dari koleksi | 200 |

Status gagal: 404 (ID tidak ada), 409 (media sudah ada di koleksi), 422 (validasi gagal).

## Ketentuan Demo dan letak kodenya

| Ketentuan | Letak |
|---|---|
| Repo GitHub + project Laravel | Langkah upload di atas |
| Dua entitas dengan relasi belongsToMany | `app/Models/Media.php` (`users()`) dan `app/Models/User.php` (`media()`) |
| Migration + foreign key + pivot | `database/migrations/..._create_media_table.php` dan `..._create_media_user_table.php` |
| Controller CRUD standar (index, show, store, update, destroy) | `app/Http/Controllers/MediaController.php` |
| Form Request Validation (POST/PATCH, judul wajib dan tidak duplikat) | `app/Http/Requests/StoreMediaRequest.php` |
| API Resource + whenLoaded | `app/Http/Resources/MediaResource.php` dan `UserResource.php` |
| Route di routes/api.php dan `route:list` | `routes/api.php` |
| Custom action di luar CRUD (attach/detach) | `UserController::addToCollection()` dan `removeFromCollection()` |

## Rubrik dan buktinya

| Aspek | Poin | Yang ditunjukkan |
|---|---|---|
| CRUD API lengkap dengan status code tepat | 20 | Request 01-07 (200, 201), request 12 (404) |
| Eloquent Relationship (migration, relasi, eager loading) | 20 | Pivot `media_user`, `Media::with('users')` di `index()`, `$media->load('users')` di `show()` |
| Form Request Validation dan error 422 | 15 | Request 08-11, 19, 20 |
| API Resource (whenLoaded) | 10 | `users` hanya muncul jika relasi sudah di-load, `episodes` hanya untuk Drama, `duration` hanya untuk Film |
| Custom action attach/detach | 10 | Request 14, 15, 17 |
| Pengujian happy path dan failure path (Postman) | 5 | Collection: 22 request, grup A-D |

## Jawaban singkat untuk pertanyaan aslab

- **Beda `hasMany` dan `belongsToMany`?** `hasMany` satu-ke-banyak (foreign key ada di tabel anak). `belongsToMany` banyak-ke-banyak dan butuh tabel pivot, di sini `media_user`.
- **Kenapa pakai pivot?** Satu media bisa dikoleksi banyak pengguna, dan satu pengguna bisa mengoleksi banyak media.
- **Apa itu eager loading?** `with('users')` memuat relasi sekaligus dalam satu query tambahan, supaya tidak terjadi query berulang untuk tiap baris (masalah N+1).
- **Apa gunanya `attach()` dan `detach()`?** Menambah dan menghapus baris di tabel pivot tanpa membuat atau menghapus data Media.
- **Kenapa 409 bukan 422 untuk duplikat koleksi?** Request-nya valid secara format, tetapi bertentangan dengan data yang sudah ada.
- **Apa itu `whenLoaded`?** Menyertakan relasi di JSON hanya jika sudah di-load, sehingga tidak memicu query tambahan.
- **Apa itu Route Model Binding?** Parameter seperti `Media $media` otomatis dicari berdasarkan ID di URL, dan jika tidak ada langsung 404.
- **Kenapa pakai Form Request?** Validasi dipisah dari controller dan berjalan otomatis sebelum method controller dieksekusi.

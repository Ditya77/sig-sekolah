# Aplikasi Sistem Informasi Geografis (SIG) Pemetaan Sekolah

## Identitas
Nama: Muhfidz Aditya Rahman  
NIM: C030325091  
Program Studi: Teknik Informatika  

## Deskripsi
Aplikasi Sistem Informasi Geografis (SIG) ini merupakan mini aplikasi berbasis Laravel dan Leaflet.js yang dikembangkan untuk memetakan sebaran titik lokasi sekolah (SD, SMP, SMA, SMK) di Kota Banjarmasin. Aplikasi ini dilengkapi dengan fitur manajemen data sekolah, visualisasi peta interaktif, filter berdasarkan jenjang dan status kepemilikan, pencarian nama sekolah dengan navigasi otomatis, pelacakan koordinat GPS perangkat, klasterisasi marker, kontrol peralihan basemap, serta penerapan sistem keamanan **Role-Based Access Control (RBAC)**, pembatasan otorisasi tingkat data via **Policy**, dan fitur **Ubah Kata Sandi** sebagai latihan mandiri.

## Fitur Utama & Latihan Mandiri
- **Manajemen Data Sekolah (CRUD)**: Pengelolaan data sekolah lengkap dengan atribut NPSN, nama, jenjang, status, alamat, kecamatan, telepon, serta koordinat latitude dan longitude.
- **Marker Berdasarkan Jenjang**: Penanda peta (*marker*) dibedakan warnanya secara otomatis berdasarkan jenjang sekolah (SD, SMP, SMA, SMK).
- **Filter Multi-Kategori**: Fitur penyaringan data sekolah berdasarkan jenjang pendidikan dan status kepemilikan (Negeri/Swasta) secara dinamis.
- **Pencarian Lokasi Cepat**: Kolom pencarian nama sekolah interaktif yang langsung menggeser (*pan*) peta ke lokasi tujuan dan membuka *popup* informasi.
- **Integrasi GPS (Use My Location)**: Tombol deteksi lokasi mandiri pada form input untuk mengambil koordinat titik GPS perangkat secara real-time.
- **Leaflet Marker Cluster**: Pengelompokan marker otomatis menggunakan plugin *MarkerCluster* untuk merapikan kepadatan titik visual saat peta diperkecil (*zoom out*).
- **Kontrol Peta Dasar (Basemap Layers)**: Fitur pilihan kontrol layer (`L.control.layers`) untuk mengganti latar belakang peta antara OpenStreetMap Standard dan Topographic.
- **Role-Based Access Control (RBAC)**: Pengamanan rute dan hak akses aplikasi berdasarkan peran pengguna (*Admin*, *Dinas*, dan *Sekolah*).
- **Otorisasi Data (Policy)**: Pembatasan hak akses di tingkat model agar akun *Sekolah* hanya dapat mengelola data profil sekolah miliknya sendiri.
- **Latihan Mandiri (Ubah Kata Sandi)**: Fitur keamanan bagi pengguna untuk mengubah kata sandi akun yang sedang aktif dengan validasi sandi saat ini (`current_password`) serta enkripsi otomatis (`Hash::make()`).

## Daftar Route (`php artisan route:list`)
- `GET|HEAD` `/` Halaman utama peta publik SIG (`PetaController@index`)
- `GET|HEAD` `data/sekolah` Endpoint data GeoJSON sekolah (`PetaController@geojson`)
- `GET|HEAD` `login` Halaman form login (`AuthController@showLogin`)
- `POST` `login` Proses autentikasi login pengguna
- `POST` `logout` Proses logout pengguna (`AuthController@logout`)
- `GET|HEAD` `dashboard` Halaman dashboard utama pengguna terautentikasi
- `GET|HEAD` `ubah-password` Form ubah kata sandi (`AuthController@editPassword`)
- `PUT` `ubah-password` Proses pembaruan kata sandi (`AuthController@updatePassword`)
- `GET|HEAD` `admin/sekolah` Menampilkan daftar data sekolah (Khusus Admin)
- `GET|HEAD` `admin/sekolah/create` Form tambah data sekolah baru
- `POST` `admin/sekolah` Menyimpan data sekolah baru ke database
- `GET|HEAD` `admin/sekolah/{sekolah}/edit` Form edit data sekolah
- `PUT|PATCH` `admin/sekolah/{sekolah}` Memperbarui data sekolah
- `DELETE` `admin/sekolah/{sekolah}` Menghapus data sekolah
- `GET|PUT` `sekolah/{sekolah}/profil` Edit profil sekolah (Role: *dinas* & *sekolah*, dilindungi Policy)

## Perintah Artisan yang Sering Digunakan

php artisan make:model Sekolah -m
php artisan make:controller Admin/SekolahController --resource
php artisan make:controller PetaController
php artisan make:seeder SekolahSeeder
php artisan migrate:fresh --seed
php artisan route:list
php artisan test
php artisan serve

## Cara Menjalankan Project

1. Install dependency PHP:

composer install

2. Salin file konfigurasi environment dan generate key:

cp .env.example .env
php artisan key:generate

3. Konfigurasi koneksi database MySQL pada file `.env`, lalu jalankan migrasi dan seeder:

php artisan migrate:fresh --seed

4. Jalankan server lokal Laravel:

php artisan serve

5. Buka browser dan akses aplikasi melalui:

http://127.0.0.1:8000
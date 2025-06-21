# 📷 camera_2.0 - Human Detection & Recording System

## 🔍 Deskripsi Singkat
`camera_2.0` adalah aplikasi berbasis web (menggunakan Laravel + Livewire) untuk:
- Deteksi manusia secara real-time melalui kamera browser
- Rekam video saat manusia terdeteksi
- Upload otomatis ke server
- Tampilkan log rekaman dan video di panel Back Office

---

## 🧩 Teknologi yang Digunakan
- Laravel 10 + Livewire
- Bootstrap 5
- JavaScript (Vanilla)
- TensorFlow.js + COCO-SSD (untuk deteksi manusia)
- MediaRecorder API (rekam canvas)
- File Storage Laravel (local disk `public`)

---

## 🚦 Alur Aplikasi

### 1. Front Office (`/fo`)
- Akses halaman `/fo` (FrontOffice)
- Aplikasi akan:
  - Menampilkan dropdown pilihan kamera
  - Memuat model COCO-SSD TensorFlow
  - Mendeteksi objek manusia dari video
  - Jika terdeteksi:
    - Rekam video (dengan audio dari mikrofon)
    - Kirim hasil rekaman ke API `/api/upload`

### 2. API Upload
- Disediakan endpoint: `POST /api/upload`
- Menerima file `.webm` dan waktu upload
- Simpan ke `storage/app/public/videos/`
- Tambahkan entri log ke `storage/app/log.txt`

Contoh log:
```
Datetime:2025-06-18 14:23:11; Name:VID_20250618_142311.webm; Size:123456
```

### 3. Back Office (`/bo`)
- Komponen Livewire `BackOffice`
- Menampilkan dua tab:
  - 📹 Daftar video terdeteksi
  - 📜 Semua log upload
- Video ditampilkan dengan tombol modal preview + info waktu & size

---

## 📁 Struktur Folder Penting
```
├── app/Livewire
│   └── FrontOffice.php
│   └── BackOffice.php
│
├── app/Http/Controllers/Api/UploadController.php
├── routes/
│   └── web.php
│   └── api.php
├── resources/views/livewire/
│   └── front-office.blade.php
│   └── back-office.blade.php
├── storage/app/log.txt
├── storage/app/public/videos/
```

---

## ⚙ Format Log yang Didukung
```text
Datetime:YYYY-MM-DD HH:MM:SS; Name:FILENAME.webm; Size:BYTES
```

Log akan dicocokkan dengan nama file video dan ditampilkan di Back Office.

---

## 📌 Rencana Pengembangan (Roadmap)
- [ ] Tambah fitur pagination untuk video & log
- [ ] Dukungan export log ke CSV/Excel
- [ ] Deteksi lebih dari satu orang (counter)
- [ ] Tambah informasi lokasi (opsional)

---

## 👨‍💻 Developer
- Project oleh `daripray dev`
- Dibuat dengan ❤️ menggunakan Laravel dan teknologi modern JS

---

## 🗒 Lisensi
Open-source project untuk keperluan pembelajaran, penelitian, dan pengembangan internal.

---

## 📬 Kontak
Jika ada pertanyaan, hubungi melalui halaman ini atau email pribadi yang disediakan.

---

## ✅ Command Umum Setelah Deploy
# 1. Clear semua cache
php artisan optimize:clear

# 2. Cache ulang konfigurasi dan route
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Buat symbolic link storage ke public
php artisan storage:link

# 4. Jalankan migrasi database (opsional)
php artisan migrate --force

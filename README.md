# 🚀 Dashboard API Monitoring & Multi-Tenant System - CI4

![Status](https://img.shields.io/badge/Status-Demo-orange)
![Framework](https://img.shields.io/badge/Framework-CodeIgniter%204-blue)
![License](https://img.shields.io/badge/License-MIT-green)

Aplikasi Dashboard API Log yang dibangun menggunakan **CodeIgniter 4**. Proyek ini dirancang untuk memantau trafik harian, log aktivitas, dan visualisasi data menggunakan Chart.js dengan dukungan fitur Dark Mode.

> **⚠️ PERHATIAN:** Proyek ini berstatus **DEMO**. Beberapa konfigurasi (seperti kredensial email) harus disesuaikan secara manual di lingkungan lokal/hosting Anda.

---

## 🚀 Fitur Utama
- **📊 Visualisasi Data Dinamis**: Integrasi Chart.js (Bar, Line, & Doughnut) untuk pemantauan statistik akses API secara real-time.
- **🌙 UI/UX Modern & Adaptif**: Antarmuka responsif yang mendukung Dark Mode secara otomatis untuk kenyamanan pengguna.
- **📧 Integrasi Notifikasi**: Pengiriman notifikasi otomatis melalui SMTP Gmail, Slack, dan Telegram (Webhook).
- **🔐 Keamanan Tingkat Lanjut**: Manajemen kredensial menggunakan file .env dan autentikasi berbasis JWT/Session yang aman.
- **🛠️ Log Management & Audit**: Monitoring aktivitas API secara mendalam untuk keperluan debugging dan audit keamanan.
- **🏢 Store Management (POS-Ready)**: Manajemen data toko/cabang yang siap diintegrasikan dengan sistem Point of Sales.
- **👥 Arsitektur Multi-Tenant**: Mendukung struktur One-to-Many, memungkinkan satu akun pengguna mengelola banyak toko secara bersamaan.

---

## 🚀 Ringkasan

### 1. Arsitektur Multi-Tenant (User-Store Relationship)
- **Relasi Many-to-Many:** Mengimplementasikan hubungan antara User dan Store melalui tabel perantara `store_users`. Ini membolehkan satu pengguna menguruskan beberapa kedai dengan satu akaun.
- **Pengurusan Akses Dinamik:** Menambah fungsi untuk menambah/menghapus akses kedai secara dinamik dalam modal profil pengguna.
- **Sistem Kedai Default:** Menambahkan logik `is_default` menggunakan Radio Button untuk menentukan kedai mana yang akan dimuatkan secara automatik semasa log masuk.

### 2. Keselamatan & Pengurusan API Key
- **Two-Step Generation:** Proses penjanaan kunci API dibahagi kepada dua fasa:
    - **Fasa Input:** Menetapkan label dan IP Whitelist.
    - **Fasa Hasil:** Memaparkan *Secret Key* hanya sekali sahaja untuk tujuan keselamatan maksima.
- **Input Group UI:** Penambahbaikan reka bentuk bar input API dengan butang 'Copy' yang bersepadu (integrated) menggunakan Flexbox untuk memastikan kedudukan butang sentiasa konsisten pada semua saiz skrin.
- **Overlay Protection:** Menambah logik untuk menghalang modal tertutup secara tidak sengaja apabila pengguna klik di luar kawasan kotak modal semasa kunci API dipaparkan.

### 3. Antaramuka Pengguna (UI/UX) & Dark Mode
- **Paging & Navigasi:** Rekabentuk semula bahagian navigasi jadual (Pagination) dengan tipografi yang bersih, jarak huruf yang lebar (*tracking-widest*), dan ikon Feather yang minimalis.
- **Sinkronisasi Tema:** Menyelaraskan warna *Header* dan *Footer* jadual menggunakan class khusus `.paging-footer` untuk memastikan warna abu-abu terang (Slate 50) pada *Light Mode* dan Navy Gelap pada *Dark Mode*.
- **Sticky Footer Modal:** Struktur modal dikemas kini supaya butang tindakan (Save/Cancel) sentiasa kekal di bahagian bawah walaupun kandungan modal panjang dan perlu diskrol.

### 4. Pengurusan Data & Log Audit
- **Pembersihan Log Berasaskan Tarikh:** Menambah ciri 'Delete by Range' yang membolehkan admin memadam log aktiviti mengikut julat tarikh tertentu untuk tujuan penyelenggaraan storan.
- **Database Transactions:** Menggunakan mekanisme transaksi (transStart/transComplete) dalam CodeIgniter untuk memastikan integriti data semasa proses kemas kini profil pengguna yang kompleks.

---

## 🛠️ Teknologi Yang Digunakan
- **Backend:** PHP (CodeIgniter 4)
- **Frontend:** Tailwind CSS, JavaScript (Vanilla)
- **Icons:** Feather Icons
- **Database:** MySQL

---

## 🛠️ Prasyarat (Prerequisites)
Sebelum menjalankan proyek ini, pastikan perangkat Anda terinstall:
* PHP >= 8.1
* Composer
* MySQL / MariaDB
* Web Server (Apache/Nginx/Laragon)

---

## 📂 Struktur Folder Penting
- app/Config: Tempat konfigurasi aplikasi (Email, Database, dll).
- app/Controllers: Logika utama dashboard.
- app/Views: File tampilan (UI, Charts, Layouts).
- public/: Aset publik (CSS, JS, Images).
- writable/: Folder penyimpanan log, cache, dan session (Pastikan permission 775/777 di hosting).

---

## 🤝 Kontribusi
Ini adalah proyek terbuka untuk pembelajaran. Jika Anda menemukan bug atau ingin menambah fitur, silakan buat Pull Request atau buka Issue.

---

## 📄 Lisensi
Proyek ini di bawah lisensi MIT - bebas digunakan untuk pembelajaran.

Dibuat dengan ❤️ oleh abekz the programmer

---

## 🚀 Panduan Instalasi

1. **Clone Repository**
   ```bash
   git clone [https://github.com/renshigenoi/apiwithci.git](https://github.com/renshigenoi/apiwithci.git)
   cd apiwithci

2. **Install Library (Vendor)**
   ```bash
	composer install

3. **Konfigurasi Environment**
   ```bash
	cp env .env

4. **Finalisasi Database**
	```bash
	php spark migrate
	php spark db:seed UserSeeder

	User Demo
	email : admin@example.com 
	password : 123456 

	email : staff@example.com
	password : 123456 

5. **Jalankan Aplikasi**
   ```bash
	php spark serve
# 🚀 Dashboard API Monitoring & Multi-Tenant System - CI4

![Status](https://img.shields.io/badge/Status-Demo-orange)
![Framework](https://img.shields.io/badge/Framework-CodeIgniter%204-blue)
![License](https://img.shields.io/badge/License-MIT-green)

Aplikasi Dashboard API Log yang dibangun menggunakan **CodeIgniter 4**. Proyek ini dirancang untuk memantau trafik harian, log aktivitas, dan visualisasi data menggunakan Chart.js dengan dukungan fitur Dark Mode.

> **⚠️ PERHATIAN:** Proyek ini berstatus **DEMO**. Beberapa konfigurasi (seperti kredensial email) harus disesuaikan secara manual di lingkungan lokal/hosting Anda.

---

## 🚀 Fitur Utama & Keamanan
- **📊 Visualisasi Data Dinamis**: Integrasi Chart.js (Bar, Line, & Doughnut) untuk pemantauan statistik akses API secara real-time.
- **🔐 Dual-Layer Authentication**: Proteksi berlapis menggunakan JWT dan X-API-KEY untuk memastikan keamanan identitas pengguna sekaligus perangkat.
- **📱 Device Binding Technology**: Sistem lisensi yang memungkinkan penguncian akses API khusus untuk perangkat tertentu (Hardware Locking).
- **🏢 Multi-Tenant Store Management**: Manajemen cabang terpusat dengan isolasi data total antar toko, siap untuk ekspansi skala besar.
- **🛠️ Deep Log & Audit Trail**: Monitoring aktivitas API mendalam, mencatat siapa, kapan, dan dari perangkat mana sebuah aksi dilakukan.
- **🌙 UI/UX Modern & Adaptif**: Antarmuka responsif dengan dukungan Dark Mode otomatis untuk efisiensi kerja di berbagai kondisi cahaya.
- **📧 Integrasi Notifikasi Multichannel**: Sistem peringatan otomatis melalui SMTP Gmail, Slack, dan Telegram Webhook.

---

## 🚀 Ringkasan
### 1. Arsitektur Multi-Tenant (Manajemen User & Store)
- **Relasi Many-to-Many:** Mengimplementasikan hubungan antara User dan Store melalui tabel perantara. Hal ini memungkinkan satu pengguna mengelola banyak cabang toko hanya dengan satu akun (sentralisasi akses).
- **Manajemen Akses Dinamis:** Penambahan fitur untuk memberikan atau mencabut izin akses toko bagi pengguna secara real-time melalui antarmuka profil.
- **Default Store Selection:** Implementasi logika toko utama menggunakan Radio Button, sehingga sistem secara otomatis memuat data toko pilihan pengguna saat pertama kali login.
### 2. Keamanan & Manajemen API Key
- **Two-Step Generation:** Proses pembuatan API Key yang lebih aman melalui dua tahap:
	- **Fase Input:** Penentuan label identitas dan IP Whitelisting.
	- **Fase Output:** Menampilkan Secret Key hanya satu kali (setelah itu di-hash) untuk keamanan maksimal.
- **Store-Specific Keys:** API Key kini terikat langsung pada store_id, memastikan isolasi data yang ketat agar akses API antar cabang tidak saling tumpang tindih.
- **Overlay Protection:** Penambahan proteksi pada modal agar tidak tertutup secara tidak sengaja saat informasi sensitif (Secret Key) sedang ditampilkan.
### 3. Keamanan Berlapis (Dual-Layer Authentication)
- **Double Lock Security:** Menggabungkan validasi JWT (untuk identitas pengguna) dan X-API-KEY (untuk identitas perangkat/toko) dalam setiap permintaan data.
- **Device Binding Ready:** Penambahan kolom device_id untuk mempersiapkan sistem lisensi, sehingga akses API dapat dikunci khusus untuk perangkat tertentu saja.
- **Stateless Global Context:** Optimasi backend menggunakan Shared Services untuk mendistribusikan data identitas toko secara efisien tanpa proses decoding berulang.
### 4. Pengalaman Pengguna (UI/UX) & Mode Gelap
- **Navigasi Modern:** Desain ulang sistem Pagination pada tabel dengan tipografi yang bersih, letter-spacing yang lebar, serta penggunaan ikon Feather yang minimalis.
- **Sinkronisasi Tema Dinamis:** Penyelarasan warna komponen (Header/Footer/Modal) agar tetap kontras dan nyaman di mata, baik pada Light Mode (Slate 50) maupun Dark Mode (Navy/Deep Blue).
- **Sticky Footer Modal:** Memastikan tombol aksi (Save/Cancel) selalu berada di posisi bawah yang mudah dijangkau, meskipun konten modal sangat panjang.
### 5. Manajemen Data & Audit Log
- **Maintenance Log Otomatis:** Fitur penghapusan log berdasarkan rentang tanggal (Delete by Range) untuk menjaga performa database dan kapasitas penyimpanan.
- **Integritas Database:** Penggunaan mekanisme Database Transactions (transStart/transComplete) pada CodeIgniter untuk menjamin data tetap konsisten dan mencegah korupsi data saat terjadi gangguan proses.

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

# 🚀 Dashboard API Monitoring - CI4

![Status](https://img.shields.io/badge/Status-Demo-orange)
![Framework](https://img.shields.io/badge/Framework-CodeIgniter%204-blue)
![License](https://img.shields.io/badge/License-MIT-green)

Aplikasi Dashboard API Log yang dibangun menggunakan **CodeIgniter 4**. Proyek ini dirancang untuk memantau trafik harian, log aktivitas, dan visualisasi data menggunakan Chart.js dengan dukungan fitur Dark Mode.

> **⚠️ PERHATIAN:** Proyek ini berstatus **DEMO**. Beberapa konfigurasi (seperti kredensial email) harus disesuaikan secara manual di lingkungan lokal/hosting Anda.

---

## ✨ Fitur Utama
- **📊 Visualisasi Data**: Integrasi Chart.js (Bar, Line, & Doughnut) untuk statistik harian.
- **🌙 UI/UX Modern**: Mendukung Dark Mode dan desain responsif.
- **📧 Sistem Email**: Notifikasi otomatis via SMTP Gmail.
- **🔐 Keamanan**: Konfigurasi sensitif menggunakan file `.env`.
- **🛠️ Log Management**: Monitoring aktivitas API secara real-time.

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

4. **Jalankan Aplikasi**
   ```bash
	php spark serve
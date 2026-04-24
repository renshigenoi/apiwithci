# Dashboard API Log - CodeIgniter 4

Dashboard untuk memantau traffic API, log harian, dan distribusi request user secara real-time.

## Fitur Utama
* Chart.js integration (Bar, Line, & Doughnut Charts).
* Dark Mode support.
* Email Notification (SMTP via Gmail).
* Auth System (Admin & Staff roles).

## Prasyarat (Prerequisites)
Pastikan server Anda memenuhi spesifikasi berikut:
* PHP 8.1 atau lebih tinggi.
* Extension PHP: `intl`, `mbstring`, `curl`.
* MySQL/MariaDB.
* [Composer](https://getcomposer.org/) (Untuk manajemen library).

## Cara Instalasi
1. **Clone Repository**
   ```bash
   git clone [https://github.com/renshigenoi/apiwithci.git](https://github.com/renshigenoi/apiwithci.git)
   cd apiwithci

2. **Install Library (Vendor)**
	composer install

3. **Konfigurasi Environment**
	cp env .env

4. **Jalankan Aplikasi**
	php spark serve
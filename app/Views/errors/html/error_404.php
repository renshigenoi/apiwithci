<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>404 - Page Not Found | API Admin Panel</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/feather-icons"></script>
  <link rel="stylesheet" href="<?= base_url('css/dashboard.css') ?>">
  <script src="<?= base_url('js/header.js') ?>"></script>
</head>
<body class="bg-mesh min-h-screen flex items-center justify-center p-6">

  <div class="max-w-md w-full text-center">
    <div class="relative mb-8">
      <h1 class="text-[120px] font-black text-blue-600/10 dark:text-blue-500/10 leading-none select-none">404</h1>
      <div class="absolute inset-0 flex items-center justify-center">
        <div class="bg-blue-600 p-4 rounded-[2rem] shadow-2xl shadow-blue-500/40 rotate-12">
           <i data-feather="frown" class="w-12 h-12 text-white"></i>
        </div>
      </div>
    </div>

    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-3">
      Halaman Tidak Ditemukan
    </h2>
    <p class="text-gray-500 dark:text-gray-400 font-medium mb-10 leading-relaxed">
      Maaf, halaman yang Anda cari tidak ada atau telah dipindahkan. Pastikan URL sudah benar.
    </p>

    <div class="flex flex-col sm:flex-row gap-3 justify-center">
      <a href="<?= base_url('dashboard') ?>" class="inline-flex items-center justify-center px-8 py-4 bg-gray-900 dark:bg-blue-600 hover:bg-black dark:hover:bg-blue-700 text-white font-bold rounded-2xl shadow-xl active:scale-95 transition-all">
        <i data-feather="home" class="w-4 h-4 mr-2"></i>
        Kembali ke Beranda
      </a>
      <button onclick="history.back()" class="inline-flex items-center justify-center px-8 py-4 bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 font-bold rounded-2xl border border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all active:scale-95 shadow-sm">
        <i data-feather="arrow-left" class="w-4 h-4 mr-2"></i>
        Kembali
      </button>
    </div>

    <p class="mt-16 text-[11px] font-bold text-gray-400 uppercase tracking-[0.3em]">
      &copy; <?= date('Y') ?> <span class="text-gray-500 dark:text-gray-400">Abekz Computer</span>
    </p>
  </div>

  <script>
    feather.replace();
  </script>
</body>
</html>
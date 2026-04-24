<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Login | API Admin Panel</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://unpkg.com/feather-icons"></script>
  <link rel="stylesheet" href="<?= base_url('css/dashboard.css') ?>">
  <script src="<?= base_url('js/header.js') ?>"></script>
</head>
<body class="bg-mesh min-h-screen flex items-center justify-center p-6">
  <div class="w-full max-w-md relative">
    <div class="bg-white dark:bg-gray-800 rounded-[3rem] p-10 shadow-soft border border-white dark:border-gray-700 relative overflow-hidden transition-all duration-300">
      <button type="button" onclick="toggleDarkMode()" class="absolute top-8 right-8 p-3 bg-gray-50 dark:bg-gray-700 rounded-2xl text-gray-500 dark:text-yellow-400 hover:scale-110 active:scale-95 transition-all border border-gray-100 dark:border-gray-600 shadow-sm z-20">
        <i id="themeIcon" data-feather="moon" class="w-5 h-5"></i>
      </button>
      <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-500/5 rounded-full blur-3xl"></div>
      <div class="relative z-10">
        <div class="flex items-center space-x-3 mb-10">
          <div class="bg-blue-600 p-2 rounded-xl shadow-lg shadow-blue-500/40">
            <i data-feather="zap" class="w-6 h-6 text-white"></i>
          </div>
          <span class="text-xl font-black text-gray-900 dark:text-white tracking-tighter">API PANEL</span>
        </div>
        <div class="mb-8">
          <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Sign In</h1>
          <p class="text-gray-500 dark:text-gray-400 font-medium text-sm mt-1">Gunakan kredensial Anda untuk masuk</p>
        </div>
        <form id="loginForm" class="space-y-5" onsubmit="return false;">
          <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 ml-1">Email</label>
            <input type="email" id="email" placeholder="your email address" 
              class="w-full px-5 py-4 bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 text-gray-900 dark:text-white outline-none transition-all placeholder:text-gray-300 dark:placeholder:text-gray-600" required>
          </div>
          <div>
            <div class="flex justify-between items-center mb-2 ml-1">
              <label class="text-sm font-bold text-gray-700 dark:text-gray-300">Password</label>
              <a href="javascript:void(0)" onclick="openForgotModal()" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">Lupa?</a>
            </div>
            <input type="password" id="password" placeholder="••••••••" 
              class="w-full px-5 py-4 bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 text-gray-900 dark:text-white outline-none transition-all" required>
          </div>
          <div class="pt-2">
            <button type="submit" class="w-full bg-gray-900 dark:bg-blue-600 hover:bg-black dark:hover:bg-blue-700 text-white font-bold py-4 rounded-2xl shadow-xl active:scale-[0.98] transition-all flex items-center justify-center">
              Login ke Dashboard
            </button>
          </div>
        </form>
      </div>
    </div>
    <div id="forgotPasswordModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-[100] p-4 transition-all duration-300">
      <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl w-full max-w-sm transform transition-all overflow-hidden border border-white dark:border-gray-700">
        <div class="p-8">
          <div class="text-center mb-6">
            <div class="bg-amber-100 dark:bg-amber-900/30 w-16 h-16 rounded-2xl mx-auto flex items-center justify-center mb-4">
              <i data-feather="help-circle" class="w-8 h-8 text-amber-600 dark:text-amber-400"></i>
            </div>
            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white">Reset Password</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Masukkan email terdaftar untuk menerima instruksi reset.</p>
          </div>
          <form id="forgotForm" onsubmit="handleForgotPassword(event)" class="space-y-4">
            <div>
              <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1">Email Terdaftar</label>
              <input type="email" id="forgotEmail" placeholder="nama@email.com" 
                class="w-full px-5 py-4 bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 text-gray-900 dark:text-white outline-none transition-all" required>
            </div>
            <div class="pt-2 flex flex-col gap-3">
              <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-500/30 active:scale-95 transition-all">
                Kirim Link Reset
              </button>
              <button type="button" onclick="closeForgotModal()" class="w-full bg-transparent text-gray-400 dark:text-gray-500 font-bold py-2 rounded-2xl hover:text-gray-600 dark:hover:text-gray-300 transition-all text-sm">
                Kembali ke Login
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="text-center mt-10">
      <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
        &copy; <?= date('Y') ?> <span class="text-gray-500 dark:text-gray-400">Abekz Computer</span>
      </p>
    </div>
  </div>
  <script src="<?= base_url('js/forget.js') ?>"></script>
  <script type="module" src="<?= base_url('js/login.js') ?>"></script>
</body>
</html>
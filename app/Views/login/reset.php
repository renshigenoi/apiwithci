<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Reset Password | API Admin Panel</title>
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
            <i data-feather="lock" class="w-6 h-6 text-white"></i>
          </div>
          <span class="text-xl font-black text-gray-900 dark:text-white tracking-tighter">RESET PASSWORD</span>
        </div>
        <div class="mb-8">
          <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Buat Password Baru</h1>
          <p class="text-gray-500 dark:text-gray-400 font-medium text-sm mt-1">Gunakan kombinasi password yang kuat.</p>
        </div>
        <form id="resetForm" class="space-y-5">
          <input type="hidden" id="token" value="<?= $token ?>">
          <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 ml-1">Password Baru</label>
            <input type="password" id="newPassword" placeholder="••••••••" class="w-full px-5 py-4 bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 text-gray-900 dark:text-white outline-none transition-all" required>
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 ml-1">Konfirmasi Password</label>
            <input type="password" id="confirmPassword" placeholder="••••••••" class="w-full px-5 py-4 bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 text-gray-900 dark:text-white outline-none transition-all" required>
          </div>
          <div class="pt-2">
            <button type="submit" class="w-full bg-gray-900 dark:bg-blue-600 hover:bg-black dark:hover:bg-blue-700 text-white font-bold py-4 rounded-2xl shadow-xl active:scale-[0.98] transition-all">
              Update Password
            </button>
          </div>
        </form>
      </div>
    </div>
    <div class="text-center mt-10">
        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
            &copy; <?= date('Y') ?> <span class="text-gray-500 dark:text-gray-400">Abekz Computer</span>
        </p>
    </div>
  </div>
  <script src="<?= base_url('js/forget.js') ?>"></script>
</body>
</html>
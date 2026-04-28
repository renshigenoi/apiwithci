<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel | API Management</title>
  <link rel="stylesheet" href="<?= base_url('css/dashboard.css?v=1.0.1') ?>">
  <link rel="stylesheet" href="<?= base_url('tailwindcss/output.css') ?>">
  <link rel="stylesheet" href="<?= base_url('sweetalert/sweetalert2.min.css') ?>">
  <script src="<?= base_url('sweetalert/sweetalert2.all.min.js') ?>"></script>
  <script src="<?= base_url('js/chart.js') ?>"></script>
  <script src="<?= base_url('js/feather/feather.min.js') ?>"></script>
</head>
<body class="bg-[#f8fafc] text-gray-900 antialiased">

  <div class="lg:hidden bg-gray-900 text-white p-4 flex justify-between items-center sticky top-0 z-[100] shadow-md">
    <div class="h-16 flex items-center px-6 text-2xl font-bold border-b border-gray-800 shrink-0">
      <div class="bg-blue-600 p-1.5 rounded-lg mr-3">
        <i data-feather="zap" class="w-5 h-5 text-white"></i>
      </div>
      <a href="#" class="cursor-pointer" onclick="showSection('dashboardSection')"><span class="text-white tracking-tight">My API</span></a>
    </div>
    <button onclick="toggleSidebar()" class="cursor-pointer p-2 hover:bg-gray-800 rounded-xl transition-all">
      <i data-feather="menu"></i>
    </button>
  </div>

  <div class="flex">
    <?= view('section/menu'); ?>
    <main class="flex-1 lg:ml-72 p-4 lg:p-10 min-h-screen overflow-x-hidden scrollbar-hide">
      <?= view('section/dashboard'); ?>
      <?= view('section/apikeys'); ?>
      <?= view('section/apilogs'); ?>
      <?= view('section/users'); ?>
      <?= view('section/store'); ?>
    </main>
    <?= view('modal/users'); ?>
    <?= view('modal/storeuser'); ?>
    <?= view('modal/apikeys'); ?>
    <?= view('modal/store'); ?>
  </div>

  <button id="backToTop" class="cursor-pointer fixed bottom-20 right-6 z-[60] bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full shadow-2xl transition-all duration-300 translate-y-20 opacity-0 focus:outline-none active:scale-90 lg:bottom-10">
    <i data-feather="arrow-up"></i>
  </button>

  <script src="<?= base_url('js/main.js?v=1.0') ?>"></script>
  <script type="module" src="<?= base_url('js/dashboard.js?v=1.0.1') ?>"></script>
  <script type="module" src="<?= base_url('js/users.js?v=1.0.1') ?>"></script>
</body>
</html>
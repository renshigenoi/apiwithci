<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel | API Management</title>
  <link rel="shortcut icon" href="<?= base_url('abekz.png') ?>">
  <link rel="stylesheet" href="<?= base_url('css/dashboard.css?v=1.0.2') ?>">
  <link rel="stylesheet" href="<?= base_url('tailwindcss/output.css') ?>">
  <link rel="stylesheet" href="<?= base_url('sweetalert/sweetalert2.min.css') ?>">
  <script src="<?= base_url('sweetalert/sweetalert2.all.min.js') ?>"></script>
  <script src="<?= base_url('js/chart.js') ?>"></script>
  <script src="<?= base_url('js/feather/feather.min.js') ?>"></script>
</head>
<body class="bg-[#f8fafc] text-gray-900 antialiased">

  <div class="lg:hidden bg-gray-900/95 backdrop-blur-sm text-white p-3 flex justify-between items-center sticky top-0 z-[100] shadow-lg border-b border-gray-800">
    <div class="flex items-center gap-3">
      <div class="p-1 bg-gray-800 rounded-lg shadow-inner">
        <img src="<?= base_url('abekz.png') ?>" class="w-10 h-10 object-contain">
      </div>
      <a href="#" class="flex flex-col" onclick="showSection('dashboardSection')">
        <span class="text-lg font-bold leading-none tracking-tight">My API</span>
        <span class="text-[10px] text-cyan-400 font-medium uppercase tracking-widest mt-1">by Abekz Comp</span>
      </a>
    </div>
    <button onclick="toggleSidebar()" class="p-2.5 hover:bg-gray-800 active:scale-95 rounded-xl transition-all border border-gray-800 shadow-sm">
      <i data-feather="menu" class="w-6 h-6 text-gray-300"></i>
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
      <?= view('section/apidocs'); ?>
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
  <script type="module" src="<?= base_url('js/dashboard.js?v=1.0.2') ?>"></script>
  <script type="module" src="<?= base_url('js/users.js?v=1.0.2') ?>"></script>
</body>
</html>
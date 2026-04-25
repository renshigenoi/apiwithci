
    <aside id="sidebar" class="w-72 bg-gray-900 text-gray-300 flex flex-col fixed top-0 left-0 z-[110] -translate-x-full lg:translate-x-0 shadow-2xl border-r border-gray-800">
      <div class="h-16 flex items-center px-4 text-2xl font-bold border-b border-gray-700 bg-gray-800 shrink-0">
        My API
      </div>
      <div class="menu-container flex-1 overflow-y-auto custom-scrollbar py-6">
        <nav class="px-4 space-y-1.5">
          <p class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em] px-4 mb-3 mt-2">
            Main Menu
          </p>
          <button onclick="showSection('dashboardSection')" class="menu-item group w-full flex items-center px-4 py-3 rounded-xl transition-all duration-200 hover:bg-gray-800 hover:text-white" id="menuDashboard">
            <i data-feather="grid" class="w-5 h-5 mr-3"></i>
            <span class="font-semibold text-sm">Dashboard</span>
          </button>
          <button onclick="showSection('storeSection')" class="menu-item group w-full flex items-center px-4 py-3 rounded-xl transition-all duration-200 hover:bg-gray-800 hover:text-white" id="menuStore">
            <i data-feather="package" class="w-5 h-5 mr-3"></i>
            <span class="font-semibold text-sm">Store Management</span>
          </button>
          <button onclick="showSection('userSection')" class="menu-item group w-full flex items-center px-4 py-3 rounded-xl transition-all duration-200 hover:bg-gray-800 hover:text-white" id="menuUser">
            <i data-feather="users" class="w-5 h-5 mr-3"></i>
            <span class="font-semibold text-sm">User Management</span>
          </button>
          <button onclick="showSection('APIKeySection')" class="menu-item group w-full flex items-center px-4 py-3 rounded-xl transition-all duration-200 hover:bg-gray-800 hover:text-white" id="menuAPIKey">
            <i data-feather="key" class="w-5 h-5 mr-3"></i>
            <span class="font-semibold text-sm">API Credentials</span>
          </button>
          <button onclick="showSection('logsSection')" class="menu-item group w-full flex items-center px-4 py-3 rounded-xl transition-all duration-200 hover:bg-gray-800 hover:text-white" id="menuLogs">
            <i data-feather="activity" class="w-5 h-5 mr-3"></i>
            <span class="font-semibold text-sm">API Access Logs</span>
          </button>
        </nav>
      </div>
      <div class="p-6 border-t border-gray-800 mt-auto bg-gray-900 shrink-0 pb-safe">
        <button id="logoutBtn" class="w-full flex items-center justify-center bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white py-3 rounded-xl font-bold transition-all active:scale-95 border border-red-500/20">
          <i data-feather="log-out" class="w-4 h-4 mr-2"></i> Logout
        </button>
      </div>
    </aside>

    <div id="overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden"></div>


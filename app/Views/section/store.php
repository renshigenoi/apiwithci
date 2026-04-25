
      <section id="storeSection" class="hidden">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
          <div class="p-6 border-b border-gray-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
              <h2 class="text-lg font-extrabold text-gray-800">Store Management</h2>
              <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mt-1">
                Manage store information and contacts
              </p>
            </div>
            <button onclick="openAddStoreModal()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl text-sm font-bold transition-all flex items-center shadow-lg shadow-red-500/25 active:scale-95">
              <i data-feather="box" class="w-4 h-4 mr-2"></i> Add New Store
            </button>
          </div>
          <div class="p-6 border-b border-gray-50 bg-gray-50/30 flex flex-col sm:flex-row justify-between gap-4">
            <div class="relative w-full sm:w-1/3">
              <span class="absolute inset-y-0 left-4 flex items-center text-gray-400">
                <i data-feather="search" class="w-4 h-4"></i>
              </span>
              <input type="text" id="searchInput" placeholder="Search name or code..." class="pl-10 border border-gray-200 p-2.5 rounded-lg w-full text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
            </div>
            <div class="flex items-center space-x-3">
              <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">Show</label>
              <select id="usersPerPage" class="border border-gray-200 py-3 pl-4 pr-10 rounded-xl text-sm font-bold bg-white focus:ring-4 focus:ring-blue-500/10 outline-none">
                <option value="15">15 Rows</option>
                <option value="25">25 Rows</option>
                <option value="all">All Data</option>
              </select>
            </div>
          </div>
          <div id="storeLoading" class="hidden text-center py-8 text-blue-500 bg-white">
            <div class="flex flex-col items-center">
              <i data-feather="loader" class="animate-spin mb-2"></i>
              <span class="text-sm font-medium italic">Fetching stores...</span>
            </div>
          </div>
          <div class="table-responsive">
            <table class="w-full text-left border-collapse min-w-[800px]">
              <thead class="bg-slate-50 dark:bg-slate-950 border-b border-slate-200 dark:border-slate-800">
                <tr>
                  <th class="px-6 py-4">Store Name</th>
                  <th class="px-6 py-4">Store Info</th>
                  <th class="px-6 py-4">Contact Person</th>
                  <th class="px-6 py-4 text-center">Created At</th>
                  <th class="px-6 py-4 text-center">Actions</th>
                </tr>
              </thead>
              <tbody id="storeTableBody" class="divide-y divide-gray-100 text-sm">
              </tbody>
            </table>
          </div>
          <div class="p-4 lg:p-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <span id="storePageInfo" class="text-xs font-bold text-gray-500 uppercase tracking-wider">
              Showing 0 of 0 stores
            </span>
            <div class="flex gap-2">
              <button onclick="prevStorePage()" id="btnPrevStore" class="flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg text-xs font-bold text-gray-600 hover:bg-gray-50 transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed uppercase tracking-widest">
                <i data-feather="chevron-left" class="w-4 h-4 mr-1"></i> Prev
              </button>
              <button onclick="nextStorePage()" id="btnNextStore" class="flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg text-xs font-bold text-gray-600 hover:bg-gray-50 transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed uppercase tracking-widest">
                Next <i data-feather="chevron-right" class="w-4 h-4 ml-1"></i>
              </button>
            </div>
          </div>
        </div>
      </section>

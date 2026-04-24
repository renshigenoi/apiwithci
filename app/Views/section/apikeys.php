
      <section id="APIKeySection" class="hidden space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
          <div class="p-6 border-b border-gray-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
              <h2 class="text-lg font-extrabold text-gray-800">API Credentials</h2>
              <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mt-1">
                Manage access keys for your integration
              </p>
            </div>
            <button onclick="openGenerateKeyModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl text-sm font-bold transition-all flex items-center shadow-lg shadow-blue-500/25 active:scale-95">
              <i data-feather="plus" class="w-4 h-4 mr-2"></i> Generate New Key
            </button>
          </div>
          <div class="p-6 border-b border-gray-50 bg-gray-50/30 flex flex-col sm:flex-row justify-between gap-4">
            <div class="relative w-full sm:w-1/3">
              <span class="absolute inset-y-0 left-4 flex items-center text-gray-400">
                <i data-feather="search" class="w-4 h-4"></i>
              </span>
              <input type="text" id="searchApiKeyInput" placeholder="Search keys..." class="pl-10 border border-gray-200 p-2.5 rounded-lg w-full text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
            </div>
            <div class="flex items-center space-x-3">
              <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">Show</label>
              <select id="apiKeysPerPage" class="border border-gray-200 py-3 pl-4 pr-10 rounded-xl text-sm font-bold bg-white focus:ring-4 focus:ring-blue-500/10 outline-none">
                <option value="15">15 Rows</option>
                <option value="25">25 Rows</option>
                <option value="all">All Data</option>
              </select>
            </div>
          </div>
          <div class="table-responsive">
            <table class="w-full text-left border-collapse min-w-[800px]">
              <thead class="bg-slate-50 dark:bg-slate-950 border-b border-slate-200 dark:border-slate-800">
                <tr>
                  <th class="px-6 py-4 w-10"><input type="checkbox" id="selectAllApi" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:bg-slate-800 dark:border-slate-700 cursor-pointer"></th>
                  <th class="px-6 py-4">Identity</th>
                  <th class="px-6 py-4">Secret Key</th>
                  <th class="px-6 py-4">Network</th>
                  <th class="px-6 py-4">Status</th>
                  <th class="px-6 py-4">Activity</th>
                  <th class="px-6 py-4 text-right">Actions</th>
                </tr>
              </thead>
              <tbody id="apiKeyTableBody" class="divide-y divide-gray-50"></tbody>
            </table>
          </div>
          <div class="p-6 border-t border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <span id="apiKeyPageInfo" class="text-xs font-bold text-gray-500 uppercase tracking-widest">
              Showing 0 of 0 keys
            </span>
            <div class="flex space-x-2">
              <button onclick="prevApiKeyPage()" id="btnPrevApiKey" class="flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-50 transition shadow-sm disabled:opacity-30 disabled:cursor-not-allowed uppercase tracking-[0.1em]">
                <i data-feather="chevron-left" class="w-4 h-4 mr-1.5"></i> 
                Prev
              </button>
              <button onclick="nextApiKeyPage()" id="btnNextApiKey" class="flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-50 transition-all active:scale-95 disabled:opacity-30 disabled:cursor-not-allowed uppercase tracking-[0.1em] shadow-sm">
                Next <i data-feather="chevron-right" class="w-4 h-4 ml-1.5"></i>
              </button>
            </div>
          </div>
        </div>

        <div id="bulkActionBar" class="fixed bottom-8 left-1/2 -translate-x-1/2 z-[100] hidden">
          <div class="bg-slate-900 dark:bg-white text-white dark:text-slate-900 px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-6 border border-slate-700 dark:border-slate-200 backdrop-blur-xl bg-opacity-90">
            <span class="text-sm font-bold border-r border-slate-700 dark:border-slate-200 pr-6">
              <span id="selectedCount">0</span> Selected
            </span>
            <div class="flex items-center gap-3">
              <button onclick="bulkApiKeyUpdateStatus(1)" class="flex items-center gap-2 px-3 py-2 hover:bg-emerald-500/10 hover:text-emerald-500 rounded-lg transition-all text-sm font-semibold">
                <i data-feather="check-circle" class="w-4 h-4"></i> Activate
              </button>
              <button onclick="bulkApiKeyUpdateStatus(2)" class="flex items-center gap-2 px-3 py-2 hover:bg-amber-500/10 hover:text-amber-500 rounded-lg transition-all text-sm font-semibold">
                <i data-feather="pause-circle" class="w-4 h-4"></i> Suspend
              </button>
              <button onclick="bulkApiKeyDelete()" class="flex items-center gap-2 px-3 py-2 hover:bg-red-500/10 hover:text-red-500 rounded-lg transition-all text-sm font-semibold">
                <i data-feather="trash-2" class="w-4 h-4"></i> Revoke
              </button>
            </div>
            <button onclick="clearApiSelection()" class="ml-2 p-2 hover:bg-slate-700 dark:hover:bg-slate-100 rounded-full transition-all">
              <i data-feather="x" class="w-4 h-4"></i>
            </button>
          </div>
        </div>
      </section>


      <section id="logsSection" class="hidden">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
          <div class="p-4 lg:p-6 border-b border-gray-50">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
              <div>
                <h2 class="text-lg font-extrabold text-gray-800">API Access Logs</h2>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mt-1">
                  Monitoring all incoming requests and system activities
                </p>
              </div>
              <div class="flex flex-wrap gap-2">
                <button onclick="window.location='/api/v1/export-excel?start='+logStartDate.value+'&end='+logEndDate.value" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition-all shadow-sm flex items-center">
                  <i data-feather="file-text" class="w-4 h-4 mr-2"></i> Excel
                </button>
                <button onclick="window.location='/api/v1/export-pdf?start='+logStartDate.value+'&end='+logEndDate.value" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition-all shadow-sm flex items-center">
                  <i data-feather="download" class="w-4 h-4 mr-2"></i> PDF
                </button>
              </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mt-6">
              <div class="md:col-span-2 flex gap-2">
                <div class="w-full">
                  <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1 ml-1">Start Date</label>
                  <input type="date" id="logStartDate" class="border border-gray-200 p-2.5 rounded-lg w-full text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div class="w-full">
                  <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1 ml-1">End Date</label>
                  <input type="date" id="logEndDate" class="border border-gray-200 p-2.5 rounded-lg w-full text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
              </div>
              <div class="flex items-end">
                <button onclick="loadLogs()" class="w-full bg-blue-600 hover:bg-blue-700 text-white p-2.5 rounded-lg text-sm font-bold transition-all flex items-center justify-center shadow-sm">
                  <i data-feather="filter" class="w-4 h-4 mr-2"></i> Apply Filter
                </button>
              </div>
            </div>
          </div>
          <div class="p-6 border-b border-gray-50 bg-gray-50/30 flex flex-col sm:flex-row justify-between gap-4">
            <div class="relative w-full sm:w-1/3">
              <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                <i data-feather="search" class="w-4 h-4"></i>
              </span>
              <input type="text" id="searchLogInput" placeholder="Search URI, IP, or Email..." class="pl-10 border border-gray-200 dark:border-gray-700 p-2.5 rounded-lg w-full text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all">
            </div>
            <div class="flex items-center gap-2">
              <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Show:</span>
              <select id="logsPerPage" class="border border-gray-200 dark:border-gray-700 p-2 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer transition-all">
                <option value="15">15 Rows</option>
                <option value="25">25 Rows</option>
                <option value="50">50 Rows</option>
              </select>
            </div>
          </div>
          <div id="logsLoading" class="hidden text-center py-10 text-blue-500 bg-white">
            <div class="flex flex-col items-center">
              <i data-feather="loader" class="animate-spin mb-2"></i>
              <span class="text-sm font-medium italic">Fetching logs...</span>
            </div>
          </div>
          <div class="table-responsive">
            <table class="w-full text-left border-collapse min-w-[800px]">
              <thead class="bg-slate-50 dark:bg-slate-950 border-b border-slate-200 dark:border-slate-800">
                <tr>
                  <th class="px-6 py-4">Requester (IP)</th>
                  <th class="px-6 py-4 text-center">Method</th>
                  <th class="px-6 py-4">Endpoint / URI</th>
                  <th class="px-6 py-4">Action</th>
                  <th class="px-6 py-4">User</th>
                  <th class="px-6 py-4 text-center">Status</th>
                  <th class="px-6 py-4 text-right">Timestamp</th>
                </tr>
              </thead>
              <tbody id="logTableBody" class="divide-y divide-gray-100 text-sm"> </tbody>
            </table>
          </div>
          <div class="p-4 lg:p-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <span id="logPageInfo" class="text-xs font-bold text-gray-500 uppercase tracking-wider"></span>
            <div class="flex gap-2">
              <button onclick="prevLogPage()" class="flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg text-xs font-bold text-gray-600 hover:bg-gray-50 transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed uppercase tracking-widest">
                <i data-feather="chevron-left" class="w-4 h-4 mr-1"></i> Prev
              </button>
              <button onclick="nextLogPage()" class="flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg text-xs font-bold text-gray-600 hover:bg-gray-50 transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed uppercase tracking-widest">
                Next <i data-feather="chevron-right" class="w-4 h-4 ml-1"></i>
              </button>
            </div>
          </div>
        </div>
      </section>

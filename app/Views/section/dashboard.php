
      <section id="dashboardSection">
        <header class="flex justify-between items-end mb-6">
          <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">System Overview</h1>
            <p class="text-gray-500 font-medium">Monitoring your API health and statistics</p>
          </div>
          <div class="flex items-center space-x-3">
            <div class="hidden sm:flex items-center bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100 text-sm font-bold text-gray-600">
              <i data-feather="calendar" class="w-4 h-4 mr-2 text-blue-500"></i>
              <span class="font-black text-gray-500" id="currentDateDisplay">Memuat...</span>
            </div>
            <button onclick="toggleDarkMode()" class="p-2.5 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 text-gray-500 dark:text-yellow-400 hover:scale-110 transition-all">
              <i id="darkModeIcon" data-feather="moon"></i>
            </button>
          </div>
        </header>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div id="cardTotalRequests" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 transition-hover hover:shadow-md">
            <div class="flex items-center justify-between mb-4">
              <div class="bg-orange-100 p-2 rounded-lg text-orange-600"><i data-feather="activity"></i></div>
              <span id="statDiffToday" class="text-xs font-bold text-green-500 bg-green-50 px-2 py-1 rounded-full">+12%</span>
            </div>
            <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Total Requests</h3>
            <p class="text-2xl font-black text-gray-900 mt-1" id="statTotalRequests">0</p>
            <div class="mt-4 flex justify-between text-[10px] font-bold text-gray-400 border-t pt-4">
              <span>TODAY: <span id="statToday" class="text-gray-600">0</span></span>
              <span>YESTERDAY: <span id="statYesterday" class="text-gray-600">0</span></span>
            </div>
          </div>
          <div id="cardActiveUsers" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 transition-hover hover:shadow-md">
            <div class="flex items-center justify-between mb-4">
              <div class="bg-blue-100 p-2 rounded-lg text-blue-600"><i data-feather="users"></i></div>
            </div>
            <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Active Users</h3>
            <p class="text-2xl font-black text-gray-900 mt-1" id="statActiveUsers">0</p>
          </div>
          <div id="cardTotalKeys" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 transition-hover hover:shadow-md">
            <div class="flex items-center justify-between mb-4">
              <div class="bg-purple-100 p-2 rounded-lg text-purple-600"><i data-feather="shield"></i></div>
            </div>
            <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">API Keys</h3>
            <p class="text-2xl font-black text-gray-900 mt-1" id="statTotalKeys">0</p>
          </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
          <div class="p-6 border-b border-gray-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
              <h2 class="text-lg font-extrabold text-gray-800">Daily Statistics</h2>
              <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mt-1">
                Real-time activity report
              </p>
            </div>
          </div>
          <div class="p-4 lg:p-6 bg-white">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
              <div class="flex flex-col">
                <div class="flex items-center mb-6">
                  <span class="w-2 h-6 bg-orange-500 rounded-full mr-3"></span>
                  <h3 class="text-md font-bold text-gray-800">Daily Traffic</h3>
                </div>
                <div class="relative h-[300px] lg:h-[380px] w-full flex items-center justify-center">
                  <canvas id="logChartDaily"></canvas>
                </div>
              </div>
              <div class="flex flex-col">
                <div class="flex items-center mb-6">
                  <span class="w-2 h-6 bg-purple-500 rounded-full mr-3"></span>
                  <h3 class="text-md font-bold text-gray-800">Distribution by Email</h3>
                </div>
                <div class="relative h-[300px] lg:h-[380px] w-full flex items-center justify-center">
                  <canvas id="logChartByEmailDaily"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden mb-6">
          <div class="p-6 border-b border-gray-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex flex-col lg:flex-row lg:items-center gap-4">
              <div class="flex gap-2 flex-1">
                <div class="relative w-full">
                  <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1 ml-1">Start Date</label>
                  <input type="date" id="startDate" class="border border-gray-200 p-2.5 rounded-lg w-full text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div class="relative w-full">
                  <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1 ml-1">End Date</label>
                  <input type="date" id="endDate" class="border border-gray-200 p-2.5 rounded-lg w-full text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
              </div>
              <div class="flex flex-wrap gap-2 lg:self-end">
                <button onclick="loadLogs()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-sm flex-1 lg:flex-none">
                  Filter
                </button>
                <button onclick="window.location='/api/v1/export-excel?start='+startDate.value+'&end='+endDate.value" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-sm flex-1 lg:flex-none">
                  Excel
                </button>
                <button onclick="window.location='/api/v1/export-pdf?start='+startDate.value+'&end='+endDate.value" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-sm flex-1 lg:flex-none">
                  PDF
                </button>
              </div>
            </div>
          </div>
          <div class="p-4 lg:p-6 bg-white">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
              <div class="flex flex-col items-center">
                <h2 class="text-md font-bold mb-6 text-gray-800 self-start border-l-4 border-blue-500 pl-3">All Access Logs</h2>
                <div class="relative h-[300px] lg:h-[350px] w-full flex items-center justify-center">
                  <canvas id="logChart"></canvas>
                </div>
              </div>
              <div class="flex flex-col items-center">
                <h2 class="text-md font-bold mb-6 text-gray-800 self-start border-l-4 border-green-500 pl-3">Total API by Email</h2>
                <div class="relative h-[300px] lg:h-[350px] w-full flex items-center justify-center">
                  <canvas id="logChartByEmail"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

import { apiFetch, logout } from './auth.js';

let users               = [];
let apiKeys             = [];
let logs                = [];
let logsdaily           = [];
let logsemaildaily      = [];
let editUserId          = null;
let editStoreId         = null;
let editUserEmail       = null;
let allUsers            = [];
let allStores           = [];
let filteredApiKeys     = [];
let myChart             = null;
let myChartEmail        = null;
let myChartEmailDaily   = null;
let myChartDaily        = null;
// --- untuk paging -- //
let logsPerPage         = 15;
let storesPerPage       = 15;
let usersPerPage        = 15;
let apiKeysPerPage      = 15;
let currentLogPage      = 1;
let currentUserPage     = 1;
let currentApiKeyPage   = 1;
let currentStorePage    = 1;

// --- Helper ---
const escapeHtml = (str) => {
  if (!str) return "";
  return String(str).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
};

// Fungsi untuk mempermudah buka/tutup loading state
function showLoading(elementId, isVisible) {
  const loader  = document.getElementById(elementId);
  if (!loader) return;
  if (isVisible) {
    loader.classList.remove('hidden');
  } else {
    loader.classList.add('hidden');
  }
}

async function loadDashboardStats() {
  try {
    const stats = await apiFetch('/api/v1/logs/stats');
    if (!stats) return;
    // --- Logika Hide/Show Card User (tetap sama) ---
    const cardUsers = document.getElementById('cardActiveUsers');
    if (cardUsers) {
      if (stats.role !== 'admin') {
        cardUsers.classList.add('hidden');
        cardUsers.parentElement.classList.replace('md:grid-cols-3', 'md:grid-cols-2');
      } else {
        cardUsers.classList.remove('hidden');
        cardUsers.parentElement.classList.replace('md:grid-cols-2', 'md:grid-cols-3');
      }
    }
    // --- Update Angka Utama ---
    animateNumber('statTotalRequests', stats.total_requests);
    animateNumber('statTotalKeys', stats.total_keys);
    if (stats.role === 'admin') animateNumber('statActiveUsers', stats.active_users);
    // --- UPDATE BAGIAN INI (Agar muncul kembali) ---
    const todayEl         = document.getElementById('statToday');
    if (todayEl) todayEl.textContent = stats.today_requests.toLocaleString();
    const yestEl          = document.getElementById('statYesterday');
    if (yestEl) yestEl.textContent  = stats.yest_requests.toLocaleString();
    // Update Persentase (statDiffToday)
    const diffEl          = document.getElementById('statDiffToday');
    if (diffEl) {
      const diff          = stats.diff || 0;
      const icon          = diff >= 0 ? '▲' : '▼';
      diffEl.textContent  = `${icon} ${Math.abs(diff).toFixed(2)}%`;
      diffEl.className    = `text-[10px] font-bold px-2 py-1 rounded-full ${diff >= 0 ? 'text-green-500 bg-green-50' : 'text-red-500 bg-red-50'}`;
    }
  } catch (err) {
    console.error("Gagal memuat statistik:", err);
  }
}

// Fungsi pembantu untuk membuat angka terlihat "menghitung" saat muncul
function animateNumber(id, targetValue) {
  const el        = document.getElementById(id);
  if (!el) return;

  let startValue  = 0;
  const duration  = 1000; // 1 detik
  const stepTime  = 20;
  const steps     = duration / stepTime;
  const increment = targetValue / steps;
  const timer     = setInterval(() => {
    startValue += increment;
    if (startValue >= targetValue) {
      el.textContent  = targetValue.toLocaleString(); // Pakai pemisah ribuan
      clearInterval(timer);
    } else {
      el.textContent  = Math.floor(startValue).toLocaleString();
    }
  }, stepTime);
}

// --- Dashboard & Chart ---
async function loadLogs() {
  showLoading('logsLoading', true);
  try {
    const start     = document.getElementById('startDate').value;
    const end       = document.getElementById('endDate').value;
    const query     = (start && end) ? `?start=${start}&end=${end}` : '';
    logs            = await apiFetch(`/api/v1/logs${query}`);
    // render all logs chart
    renderLogChart();
    // render all logs by email chart
    renderLogChartByEmail();
    // render logs table
    const logRole   = (localStorage.getItem("role") || "").toLowerCase();
    const logEmail  = (localStorage.getItem("email") || "").toLowerCase();
    const logStart  = document.getElementById('logStartDate').value;
    const logEnd    = document.getElementById('logEndDate').value;
    const logQuery  = (logStart && logEnd && logRole && logEmail) ? `?role=${logRole}&email=${logEmail}&start=${logStart}&end=${logEnd}` : '';
    logs            = await apiFetch(`/api/v1/logs${logQuery}`);
    currentLogPage  = 1;
    renderLogsPage();
    // render daily logs
    const role      = (localStorage.getItem("role") || "").toLowerCase();
    const userEmail = (localStorage.getItem("email") || "").toLowerCase();
    const roleQuery = (role && userEmail) ? `?role=${role}&email=${userEmail}` : '';
    logsdaily       = (await apiFetch(`/api/v1/logs/daily-by-status${roleQuery}`)).data;
    renderLogChartDaily();
    // render daily logs by email
    logsemaildaily  = (await apiFetch('/api/v1/logs/daily-by-email')).data;
    renderLogChartByEmailDaily();
  } catch (err) {
    console.error("Gagal memuat logs:", err);
  } finally {
    showLoading('logsLoading', false);
  }
}

const getChartTextColor = () => {
  return document.documentElement.classList.contains('dark') ? '#cbd5e1' : '#475569';
};

const getChartGridColor = () => {
  return document.documentElement.classList.contains('dark') ? '#334155' : '#f1f5f9';
};

function renderLogChartByEmailDaily() {
  const canvas              = document.getElementById('logChartByEmailDaily');
  if (!canvas) return;
  if (window.myChartEmailDaily) window.myChartEmailDaily.destroy();
  const role                = (localStorage.getItem("role") || "").toLowerCase();
  const userEmail           = (localStorage.getItem("email") || "").toLowerCase();
  const isDark              = document.documentElement.classList.contains('dark');
  const textColor           = isDark ? '#94a3b8' : '#64748b';
  const borderColor         = isDark ? '#1e293b' : '#ffffff';
  const counts = {};
  if (!logsemaildaily || logsemaildaily.length === 0) return;
  logsemaildaily.forEach(item => {
    const email             = (item.user_email || "system").toLowerCase();
    if (role === "staff" && email !== userEmail) return;
    counts[email]           = (counts[email] || 0) + Number(item.total);
  });
  const labels              = Object.keys(counts);
  const data                = Object.values(counts);
  const chartColors         = [
    '#6366f1', '#10b981', '#f59e0b', '#3b82f6', '#f43f5e', '#8b5cf6', '#06b6d4'
  ];
  const ctx                 = canvas.getContext('2d');
  window.myChartEmailDaily  = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: labels,
      datasets: [{
        data: data,
        backgroundColor: chartColors,
        borderColor: borderColor,
        borderWidth: 2,
        hoverOffset: 15,
        cutout: '70%'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            // --- KUNCI PERBAIKAN DI SINI ---
            color: textColor, // Ini warna utama untuk teks legend
            usePointStyle: true,
            pointStyle: 'circle',
            padding: 15,
            font: { family: "'Plus Jakarta Sans'", size: 11, weight: '600' },
            // Update fungsi generateLabels agar warna per-item juga sinkron
            generateLabels: function(chart) {
              const data = chart.data;
              if (data.labels.length && data.datasets.length) {
                const dataset = data.datasets[0];
                return data.labels.map((label, i) => {
                  return {
                    text: `${label} (${dataset.data[i]})`,
                    fillStyle: dataset.backgroundColor[i % chartColors.length],
                    strokeStyle: dataset.backgroundColor[i % chartColors.length],
                    lineWidth: 0,
                    hidden: false,
                    index: i,
                    // Tambahkan properti color di sini untuk tiap item
                    fontColor: textColor,
                    color: textColor
                  };
                });
              }
              return [];
            }
          }
        },
        tooltip: {
          backgroundColor: isDark ? '#1e293b' : '#ffffff',
          titleColor: isDark ? '#f8fafc' : '#1e293b',
          bodyColor: isDark ? '#cbd5e1' : '#64748b',
          borderColor: isDark ? '#334155' : '#e2e8f0',
          borderWidth: 1,
          padding: 12,
          callbacks: {
            label: function(context) {
              const val = context.raw || 0;
              const total = context.dataset.data.reduce((a, b) => a + b, 0);
              const pct = Math.round((val / total) * 100);
              return ` ${val} Requests (${pct}%)`;
            }
          }
        }
      }
    }
  });
}

function renderLogChartDaily() {
  const canvas          = document.getElementById('logChartDaily');
  if (!canvas) return;
  if (window.mychartDaily) window.mychartDaily.destroy();
  const isDark          = document.documentElement.classList.contains('dark');
  const textColor       = isDark ? '#94a3b8' : '#64748b';
  const gridColor       = isDark ? '#334155' : '#e2e8f0';
  if (!logsdaily || logsdaily.length === 0) return;
  const grouped         = {};
  logsdaily.forEach(item => {
    if (!grouped[item.log_date]) grouped[item.log_date] = {};
    grouped[item.log_date][item.status_code] = item.total;
  });
  const labels          = Object.keys(grouped);
  const statusCodes     = [...new Set(logsdaily.map(item => item.status_code))];
  const getStatusColor  = (code) => {
    if (code >= 200 && code < 300) return '#10b981'; // Success
    if (code >= 400 && code < 500) return '#f59e0b'; // Warning/Client Error
    if (code >= 500) return '#ef4444';               // Danger/Server Error
    return '#64748b';
  };

  const datasets        = statusCodes.map(code => {
    const color         = getStatusColor(parseInt(code));
    return {
      label: `Status ${code}`,
      data: labels.map(date => grouped[date][code] || 0),
      borderColor: color,
      backgroundColor: isDark ? color + '20' : color + '10',
      fill: true, // Memberikan efek area di bawah garis
      tension: 0.4, // Membuat garis melengkung lembut (Smooth)
      pointRadius: 4,
      pointHoverRadius: 6,
      pointBackgroundColor: color,
      borderWidth: 3
    };
  });
  const ctx             = canvas.getContext('2d');
  window.mychartDaily   = new Chart(ctx, {
    type: 'line', // Diubah ke Line agar tren waktu lebih jelas
    data: { labels, datasets },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      interaction: {
        mode: 'index',
        intersect: false,
      },
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            color: textColor,
            usePointStyle: true,
            pointStyle: 'circle',
            font: { family: "'Plus Jakarta Sans'", size: 11, weight: '600' }
          }
        },
        tooltip: {
          backgroundColor: isDark ? '#1e293b' : '#ffffff',
          titleColor: isDark ? '#f8fafc' : '#1e293b',
          bodyColor: isDark ? '#cbd5e1' : '#64748b',
          borderColor: gridColor,
          borderWidth: 1,
          padding: 12,
          cornerRadius: 8
        }
      },
      scales: {
        x: {
          ticks: { color: textColor, font: { size: 10 } },
          grid: { display: false }
        },
        y: {
          beginAtZero: true,
          ticks: {
            color: textColor,
            font: { size: 10 },
            stepSize: 1 // Opsional: agar angka y-axis bulat
          },
          grid: { color: gridColor, drawBorder: false }
        }
      }
    }
  });
}

function renderLogChartByEmail() {
  const role          = (localStorage.getItem("role") || "").toLowerCase();
  const userEmail     = (localStorage.getItem("email") || "").toLowerCase();
  const canvas        = document.getElementById('logChartByEmail');
  if (!canvas) return;
  const isDark        = document.documentElement.classList.contains('dark');
  const textColor     = isDark ? '#94a3b8' : '#64748b';
  const borderColor   = isDark ? '#1e293b' : '#ffffff';
  // 1. Hitung data
  const counts        = {};
  logs.forEach(log => {
    const email = (log.user_email || "system").toLowerCase();
    if (role === "staff" && email !== userEmail) return;
    counts[email]     = (counts[email] || 0) + 1;
  });
  const labels        = Object.keys(counts);
  const data          = Object.values(counts);
  // 2. Palet warna professional (Emerald, Blue, Amber, Rose, Indigo, Violet)
  const chartColors   = [
    '#10b981', '#3b82f6', '#f59e0b', '#f43f5e', '#6366f1', '#8b5cf6', '#06b6d4'
  ];
  const ctx           = canvas.getContext('2d');
  if (window.myChartEmail) window.myChartEmail.destroy();
  window.myChartEmail = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: labels,
      datasets: [{
        data: data,
        backgroundColor: chartColors,
        borderColor: borderColor,
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            color: textColor,
            usePointStyle: true,
            pointStyle: 'circle',
            padding: 15,
            font: { family: "'Plus Jakarta Sans'", size: 11, weight: '600' },
            // VERSI STABIL: Menggunakan mapping manual dari data
            generateLabels: function(chart) {
              const data = chart.data;
              if (data.labels.length && data.datasets.length) {
                const dataset = data.datasets[0];
                return data.labels.map((label, i) => {
                  return {
                    text: `${label} (${dataset.data[i]})`, // Label + Angka
                    fillStyle: dataset.backgroundColor[i], // Ambil warna dari array background
                    strokeStyle: dataset.backgroundColor[i],
                    lineWidth: 0,
                    hidden: false,
                    index: i,
                    fontColor: textColor
                  };
                });
              }
              return [];
            }
          }
        },
        tooltip: {
          backgroundColor: isDark ? '#1e293b' : '#ffffff',
          titleColor: isDark ? '#f8fafc' : '#1e293b',
          bodyColor: isDark ? '#cbd5e1' : '#64748b',
          borderColor: isDark ? '#334155' : '#e2e8f0',
          borderWidth: 1,
          padding: 12,
          displayColors: true,
          cornerRadius: 8,
          callbacks: {
            label: function(context) {
              const label = context.label || '';
              const value = context.raw || 0;
              const total = context.dataset.data.reduce((a, b) => a + b, 0);
              const percentage = Math.round((value / total) * 100);
              return ` ${label}: ${value} Req (${percentage}%)`;
            }
          }
        }
      }
    }
  });
}

function renderLogChart() {
  const role        = (localStorage.getItem("role") || "").toLowerCase();
  const userEmail   = (localStorage.getItem("email") || "").toLowerCase();
  const canvas      = document.getElementById('logChart');
  if (!canvas) return;

  const isDark      = document.documentElement.classList.contains('dark');
  const textColor   = isDark ? '#94a3b8' : '#64748b';
  const gridColor   = isDark ? '#334155' : '#e2e8f0';

  const counts = {};
  logs.forEach(log => {
      const email   = (log.user_email || "").toLowerCase();
      if (role === "staff" && email !== userEmail) return;
      const code    = log.status_code || 'Unknown';
      counts[code]  = (counts[code] || 0) + 1;
  });

  const getStatusColor = (code) => {
      if (code >= 200 && code < 300) return '#10b981'; // Emerald
      if (code >= 400 && code < 500) return '#f59e0b'; // Amber
      if (code >= 500) return '#ef4444';               // Red
      return '#64748b';
  };

  const datasets = Object.keys(counts).map(code => {
      return {
          label: `Status ${code} (${counts[code]} Request)`,
          data: [counts[code]],
          backgroundColor: getStatusColor(parseInt(code)),
          borderRadius: 8,           // Membuat bar tumpul/rounded agar modern
          barThickness: 60,          // Menjaga lebar bar tetap konsisten (tidak melebar)
          maxBarThickness: 50,
      };
  });
  const ctx       = canvas.getContext('2d');
  if (window.myChart instanceof Chart) window.myChart.destroy();
  window.myChart  = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Activity Distribution'],
      datasets: datasets
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
          position: 'bottom',
          labels: {
            color: textColor,
            usePointStyle: true,
            pointStyle: 'circle',
            font: { family: "'Plus Jakarta Sans'", size: 11, weight: '600' }
          }
        },
        tooltip: {
          backgroundColor: isDark ? '#1e293b' : '#ffffff',
          titleColor: isDark ? '#f8fafc' : '#1e293b',
          bodyColor: isDark ? '#cbd5e1' : '#64748b',
          borderColor: gridColor,
          borderWidth: 1
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: { color: textColor, font: { size: 10 } },
          grid: { color: gridColor, drawBorder: false }
        },
        x: {
          ticks: { display: false }, // Sembunyikan label bawah agar tidak double dengan legend
          grid: { display: false }
        }
      }
    }
  });
}

// --- API Keys Logic ---
async function loadApiKeys() {
  showLoading('apiKeyLoading', true);
  try {
    const response = await apiFetch('/api/v1/keys');
    // Pastikan format data adalah array
    apiKeys = Array.isArray(response) ? response : (response.data || []);
    currentApiKeyPage = 1; // Reset ke hal 1
    renderApiKeyTable();
  } catch (err) {
    Swal.fire('Error', 'Gagal memuat API Keys', 'error');
  } finally {
    showLoading('apiKeyLoading', false);
  }
}

function updateApiKeyPaginationControls(totalData) {
    const rows = apiKeysPerPage === 'all' ? totalData : parseInt(apiKeysPerPage);
    const totalPages = Math.ceil(totalData / rows) || 1;
    document.getElementById('apiKeyPageInfo').textContent = `Page ${currentApiKeyPage} of ${totalPages} (${totalData} Keys)`;
    document.getElementById('btnPrevApiKey').disabled     = (currentApiKeyPage === 1);
    document.getElementById('btnNextApiKey').disabled     = (currentApiKeyPage === totalPages || apiKeysPerPage === 'all');
}

window.nextApiKeyPage = () => {
  const totalPages = Math.ceil(apiKeys.length / apiKeysPerPage);
  if (currentApiKeyPage < totalPages) {
    currentApiKeyPage++;
    renderApiKeyTable();
  }
};

window.prevApiKeyPage = () => {
  if (currentApiKeyPage > 1) {
    currentApiKeyPage--;
    renderApiKeyTable();
  }
};

// --- Users Logic ---
// Fungsi untuk load data (modifikasi fungsi fetch user Anda)
async function loadUsers() {
  showLoading('userLoading', true);
  try {
    // Asumsi API Anda mengembalikan array user
    const data      = await apiFetch('/api/v1/users');
    // Pastikan kita menyimpan array ke allUsers
    allUsers        = Array.isArray(data) ? data : (data && data.data ? data.data : []);
    currentUserPage = 1; // Reset ke hal 1 setiap refresh
    renderUserTable();
  } catch (error) {
    console.error("Failed to load users", error);
  } finally {
    showLoading('userLoading', false);
  }
}

async function loadStores() {
  showLoading('storeLoading', true);
  try {
    const data          = await apiFetch('/api/v1/store');
    allStores           = Array.isArray(data) ? data : (data && data.data ? data.data : []);
    currentStorePage    = 1; // Reset ke hal 1 setiap refresh
    renderStoreTable();
  } catch (error) {
    console.error("Failed to load stores", error);
  } finally {
    showLoading('storeLoading', false);
  }
}

function renderStoreTable() {
  const container = document.getElementById('storeTableBody');
  if (!container || !allStores) {
    if (container) container.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-gray-500">No data available</td></tr>';
    return;
  }

  const startIndex = (currentStorePage - 1) * storesPerPage;
  const endIndex   = startIndex + storesPerPage;
  // Ambil subset data
  let displayedStores = storesPerPage === 'all' ? allStores : allStores.slice(startIndex, endIndex);
  // Jika halaman kosong tapi masih ada data di halaman sebelumnya (karena filter), coba mundur halaman
  // (Logika pagination standar)
  if (displayedStores.length === 0 && allStores.length > 0 && currentStorePage > 1) {
      currentStorePage--;
      displayedStores = allStores.slice((currentStorePage - 1) * storesPerPage, currentStorePage * storesPerPage);
  }
  if (displayedStores.length === 0) {
    container.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-gray-500">No data available</td></tr>';
    return;
  }
  const role = (localStorage.getItem('role') || '').toLowerCase();
  const canDelete = role !== 'staff'; // Staff tidak bisa delete
  // Render UI Baris
  let html = displayedStores.map((store, index) => {
return `
  <tr class="group hover:bg-blue-50/50 dark:hover:bg-slate-800/40 transition-all duration-200 border-b border-slate-100 dark:border-slate-800 last:border-0">
    <td class="px-6 py-4">
      <div class="flex items-center">
        <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-blue-600 text-white dark:bg-blue-900/40 dark:text-blue-400 flex items-center justify-center font-extrabold shadow-sm border border-transparent dark:border-blue-800/50">
          ${store.name ? store.name.charAt(0).toUpperCase() : '<i data-feather="store" class="w-5 h-5"></i>'}
        </div>
        <div class="ml-4">
          <div class="text-sm font-bold text-gray-800 leading-tight whitespace-nowrap">
            ${store.name}
          </div>
          <div class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold mt-1.5 uppercase tracking-wider transition-all duration-200
            bg-slate-600 text-white shadow-sm
            dark:bg-slate-800/50 dark:text-slate-300 dark:border dark:border-slate-700/50">
            ${store.code}
          </div>
        </div>
      </div>
    </td>
    <td class="px-6 py-4">
      <div class="flex flex-col">
        <div class="flex items-center text-sm font-semibold text-blue-600 dark:text-blue-400">
          <i data-feather="mail" class="w-3.5 h-3.5 mr-2"></i>
          ${store.email}
        </div>
        <div class="flex items-center text-xs text-slate-600 dark:text-slate-500 mt-1 font-medium">
          <i data-feather="phone" class="w-3.5 h-3.5 mr-2"></i>
          ${store.phone || '-'}
        </div>
      </div>
    </td>
    <td class="px-6 py-4">
      <div class="text-sm font-bold text-slate-800 dark:text-slate-400">${store.contact_person}</div>
      <div class="text-xs text-slate-600 dark:text-slate-500 mt-0.5">${store.contact_phone || '-'}</div>
    </td>
    <td class="px-6 py-4 text-right">
      <div class="text-[11px] text-slate-600 dark:text-slate-400 font-mono font-bold">${store.created_at || '-'}</div>
    </td>
    <td class="px-6 py-4 text-right">
      <div class="flex justify-center space-x-1">
        <button onclick="openEditStore('${store.id}', '${store.name}', '${store.address}', '${store.email}', '${store.phone}', '${store.contact_person}', '${store.contact_phone}')" 
                class="p-2 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded-xl transition-all" 
                title="Edit">
          <i data-feather="edit-2" class="w-4 h-4"></i>
        </button>
        ${canDelete ? `
        <button onclick="deleteStore('${store.id}')"
                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all"
                title="Hapus">
          <i data-feather="trash-2" class="w-4 h-4"></i>
        </button>` : ''}
      </div>
    </td>
  </tr>
`;
  }).join('');

  container.innerHTML = html;
  // Update info pagination
  const totalData = allStores.length;
  updateStorePaginationControls(totalData);
  feather.replace();
}

function updateStorePaginationControls(totalData) {
    const totalPages = Math.ceil(totalData / storesPerPage) || 1;
    // Update teks info
    document.getElementById('storePageInfo').textContent = `Page ${currentStorePage} of ${totalPages} (${totalData} Stores)`;

    // Update status tombol
    document.getElementById('btnPrevStore').disabled = (currentStorePage === 1);
    document.getElementById('btnNextStore').disabled = (currentStorePage === totalPages);
}

// Fungsi Navigasi
function nextStorePage() {
  const totalPages = Math.ceil(allStores.length / storesPerPage);
  if (currentStorePage < totalPages) {
    currentStorePage++;
    renderStoreTable();
    window.scrollTo({ top: 0, behavior: 'smooth' }); // Scroll ke atas tabel saat ganti page
  }
}

function prevStorePage() {
  if (currentStorePage > 1) {
    currentStorePage--;
    renderStoreTable();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
}

// --- BULK ACTIONS LOGIC ---
function humanizePayload(payloadString) {
  if (!payloadString) return '<span class="text-slate-400 dark:text-slate-500 italic text-xs">No Data</span>';
  try {
    const data = JSON.parse(payloadString);
    let title = "Activity";
    let colorClass = "text-slate-600 dark:text-slate-300";
    let icon = "info";

    // Identifikasi Tipe Aksi untuk Label Singkat di Tabel
    if (data.action === 'REVOKE_KEYS') {
      title = data.count > 1 ? `Massive Revoke (${data.count})` : "Revoke Key";
      colorClass = "text-red-600 dark:text-red-400";
      icon = "trash-2";
    } else if (data.action === 'BULK_STATUS' || data.action === 'UPDATE_STATUS') {
      const status = data.status === 1 ? 'Activate' : 'Suspend';
      title = data.count > 1 ? `Bulk ${status} (${data.count})` : `${status} Key`;
      colorClass = data.status === 1 ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400';
      icon = data.status === 1 ? 'check-circle' : 'pause-circle';
    }

    // Return tombol yang memicu modal detail
    return `
      <button onclick="viewLogDetail('${encodeURIComponent(payloadString)}')"
              class="flex items-center gap-2 hover:opacity-80 transition-opacity group">
          <div class="p-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
              <i data-feather="${icon}" class="w-3.5 h-3.5 ${colorClass}"></i>
          </div>
          <span class="text-sm font-medium ${colorClass} underline decoration-dotted underline-offset-4">
              ${title}
          </span>
      </button>
    `;
  } catch (e) {
    return `<span class="text-slate-500 dark:text-slate-400 text-xs italic">${payloadString}</span>`;
  }
}

window.viewLogDetail = function(encodedPayload) {
  const data        = JSON.parse(decodeURIComponent(encodedPayload));
  const isDark      = document.documentElement.classList.contains('dark');
  const bgColor     = isDark ? '#0f172a' : '#f8fafc';
  const textColor   = isDark ? '#60a5fa' : '#1d4ed8';
  const borderColor = isDark ? '#1e293b' : '#e2e8f0';

  Swal.fire({
    title: `
      <div style="display: flex; align-items: center; justify-content: center; gap: 10px; color: ${isDark ? '#f1f5f9' : '#1e293b'}; width: 100%;">
          <i data-feather="activity" style="width: 20px; height: 20px; color: #3b82f6;"></i>
          <h1 style="font-weight: 700;">Log Detail</h1>
      </div>
    `,
    background: isDark ? '#1e293b' : '#ffffff',
    html: `
      <div style="text-align: left; margin-top: 15px; border-top: 1px solid ${borderColor}; padding-top: 15px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px;">
          <div>
            <p style="font-size: 10px; text-transform: uppercase; color: #94a3b8; font-weight: bold; margin: 0;">Action Type</p>
            <p style="font-size: 14px; font-weight: 600; margin: 0; color: ${isDark ? '#f1f5f9' : '#1e293b'}">${data.action}</p>
          </div>
          <div>
            <p style="font-size: 10px; text-transform: uppercase; color: #94a3b8; font-weight: bold; margin: 0;">Affected Items</p>
            <p style="font-size: 14px; font-weight: 600; margin: 0; color: ${isDark ? '#f1f5f9' : '#1e293b'}">${data.count || 1} Data</p>
          </div>
        </div>
        <div>
          <p style="font-size: 10px; text-transform: uppercase; color: #94a3b8; font-weight: bold; margin-bottom: 8px;">Affected ID List</p>
          <div style="background-color: ${bgColor}; color: ${textColor}; padding: 12px; border-radius: 12px; border: 1px solid ${borderColor}; font-family: monospace; font-size: 12px; max-height: 150px; overflow-y: auto; box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.05);">
            ${Array.isArray(data.items) ? data.items.join(', ') : data.items}
          </div>
        </div>
      </div>
    `,
    confirmButtonText: 'Understood',
    confirmButtonColor: '#3b82f6',
    customClass: {
      popup: 'rounded-3xl shadow-2xl'
    },
    didOpen: () => {
      feather.replace();
    }
  });
}

// 1. Fungsi untuk Update Tampilan Bar & Count
function updateApiBulkBar() {
  const checkedBoxes  = document.querySelectorAll('.api-row-checkbox:checked');
  const checkedCount  = checkedBoxes.length;
  const bar           = document.getElementById('bulkActionBar');
  const countLabel    = document.getElementById('selectedCount');
  if (checkedCount > 0) {
    bar.classList.remove('hidden');
    countLabel.innerText = checkedCount;
  } else {
    bar.classList.add('hidden');
    // Reset header checkbox jika semua baris tidak dicentang
    const selectAll = document.getElementById('selectAllApi');
    if (selectAll) selectAll.checked = false;
  }
}

// 2. Event Delegation (Agar tetap jalan saat tabel di-render ulang)
document.addEventListener('change', function(e) {
    // 1. Logic Select All
    if (e.target.id === 'selectAllApi') {
      const checkboxes = document.querySelectorAll('.api-row-checkbox');
      checkboxes.forEach(cb => {
        cb.checked = e.target.checked;
      });
      updateApiBulkBar();
    }
    // 2. Logic Individual Checkbox
    if (e.target.classList.contains('api-row-checkbox')) {
        updateApiBulkBar();
        // Update header checkbox jika semua individual dicentang secara manual
        const allChecked = document.querySelectorAll('.api-row-checkbox').length === 
                           document.querySelectorAll('.api-row-checkbox:checked').length;
        const selectAll = document.getElementById('selectAllApi');
        if (selectAll) selectAll.checked = allChecked;
    }
});

function clearApiSelection() {
  document.querySelectorAll('.api-row-checkbox').forEach(cb => cb.checked = false);
  const selectAll = document.getElementById('selectAllApi');
  if (selectAll) selectAll.checked = false;
  updateApiBulkBar();
}

async function bulkApiKeyUpdateStatus(status) {
  const selectedIds   = Array.from(document.querySelectorAll('.api-row-checkbox:checked')).map(cb => cb.value);
  const statusText    = status === 1 ? 'Activate' : 'Suspend';
  const result        = await Swal.fire({
    title: `Bulk ${statusText}`,
    text: `Are you sure you want to ${statusText.toLowerCase()} ${selectedIds.length} API keys?`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: status === 1 ? '#10b981' : '#f59e0b',
    confirmButtonText: `Yes, ${statusText}!`
  });

  if (result.isConfirmed) {
    try {
      // Gunakan apiFetch untuk kirim data ke backend CodeIgniter kamu
      const resData  = await apiFetch('/api/v1/bulk/apikey-status', {
        method  : 'POST',
        body    : JSON.stringify({ ids: selectedIds, status: status })
      });
      if (resData && resData.success) {
        Swal.fire('Updated!', resData.message, 'success');
        clearApiSelection();
        loadApiKeys(); // Refresh tabel
      } else {
        throw new Error(resData?.message || 'Server returned an error');
      }
    } catch (error) {
      Swal.fire('Error', 'Failed to update status: ' + error, 'error');
    }
  }
}

async function bulkApiKeyDelete() {
  const selectedIds = Array.from(document.querySelectorAll('.api-row-checkbox:checked')).map(cb => cb.value);
  const result      = await Swal.fire({
    title: 'Massive Revoke!',
    text: `Warning: You are about to delete ${selectedIds.length} API keys permanently!`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    confirmButtonText: 'Yes, Revoke All!'
  });

  if (result.isConfirmed) {
    try {
      const resData  = await apiFetch('/api/v1/bulk/apikey-delete', {
        method  : 'POST',
        body    : JSON.stringify({ ids: selectedIds })
      });
      if (resData && resData.success) {
        Swal.fire('Deleted!', resData.message, 'success');
        clearApiSelection();
        loadApiKeys(); // Refresh tabel
      } else {
        throw new Error(resData?.message || 'Server returned an error');
      }
    } catch (error) {
      Swal.fire('Error', 'Failed to delete data: ' + error, 'error');
    }
  }
}

function renderApiKeyTable(dataList = null) {
  const tbody = document.getElementById('apiKeyTableBody');
  if (!tbody) return;

  // Gunakan dataList (hasil filter) jika ada, jika tidak gunakan variabel global apiKeys
  const displayData = dataList || apiKeys;
  // Logika Paging
  const rows = apiKeysPerPage === 'all' ? displayData.length : parseInt(apiKeysPerPage);
  const start = (currentApiKeyPage - 1) * rows;
  const paginatedKeys = displayData.slice(start, start + rows);
  if (paginatedKeys.length === 0) {
    tbody.innerHTML = `<tr><td colspan="6" class="p-10 text-center text-gray-400 italic">Belum ada API Key.</td></tr>`;
    updateApiKeyPaginationControls(0);
    return;
  }

  // Gunakan logika render yang sudah kita buat sebelumnya
  tbody.innerHTML = paginatedKeys.map(u => {
    // --- Logic Status Badge (Support Dark Mode) ---
    const statusBadge = u.is_active == 1
      ? `<span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 rounded-lg text-[10px] font-extrabold uppercase tracking-widest border border-emerald-200 dark:border-emerald-500/20 shadow-sm">Active</span>`
      : `<span class="px-2.5 py-1 bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400 rounded-lg text-[10px] font-extrabold uppercase tracking-widest border border-slate-200 dark:border-slate-700 shadow-sm">Inactive</span>`;

    return `
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors border-b border-gray-50 dark:border-gray-700/50">
      <td class="px-6 py-4"><input type="checkbox" class="api-row-checkbox w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:bg-slate-800 dark:border-slate-700 cursor-pointer" value="${u.id}"></td>
      <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex flex-col">
          <span class="text-sm font-bold text-gray-800 leading-tight">${u.key_label}</span>
          <span class="text-[11px] text-blue-500 dark:text-blue-400 font-medium mt-0.5">${escapeHtml(u.user_email)}</span>
        </div>
      </td>
      <td class="px-6 py-4 whitespace-nowrap">
        <code class="bg-slate-100/50 dark:bg-slate-800/40 text-slate-700 dark:text-slate-300 px-3 py-2 rounded-xl text-[11px] font-mono border border-slate-200 dark:border-slate-700 shadow-sm transition-all group-hover:bg-white dark:group-hover:bg-slate-800">
          <span class="text-slate-600 dark:text-slate-600">${u.api_key.substring(0, 12)}</span>
          <span class="opacity-30 text-slate-400">••••••••</span>
        </code>
      </td>
      <td class="px-6 py-4 whitespace-nowrap"><span class="text-xs font-mono text-gray-800 leading-tight">${u.ip_whitelist || '<span class="opacity-30">Any IP</span>'}</span></td>
      <td class="px-6 py-4 whitespace-nowrap">${statusBadge}</td>
      <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-xs text-gray-600 dark:text-gray-400 font-medium">${u.last_used_at
          ? `<span class="font-mono text-[11px]">${u.last_used_at}</span>`
          : '<span class="text-[10px] uppercase tracking-tighter text-gray-400 italic">Never Used</span>'}
        </div>
      </td>
      <td class="px-6 py-4 whitespace-nowrap text-right">
        <button onclick="revokeApiKey(${u.id})" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all" title="Revoke Access">
          <i data-feather="slash" class="w-4 h-4"></i>
        </button>
      </td>
    </tr>`;
  }).join('');

  updateApiKeyPaginationControls(displayData.length);
  updateApiBulkBar();
  feather.replace();
}

function renderUserTable() {
    const tbody = document.getElementById('userTableBody');
    if (!tbody) return;

    // Hitung data yang akan ditampilkan
    const start = (currentUserPage - 1) * usersPerPage;
    const end = start + usersPerPage;
    const paginatedUsers = allUsers.slice(start, end);

    if (paginatedUsers.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" class="p-10 text-center text-gray-400 italic">No users found.</td></tr>`;
        updateUserPaginationControls(0);
        return;
    }

    // Render baris tabel (Gunakan kode render yang sebelumnya kita buat)
    tbody.innerHTML = paginatedUsers.map(u => {
      // --- Logic Badge Role (Support Dark Mode) ---
      const roleBadge = u.role.toLowerCase() === 'admin'
        ? `<span class="px-2.5 py-1 bg-violet-50 text-violet-700 border border-violet-200 dark:bg-violet-500/10 dark:text-violet-400 dark:border-violet-500/20 rounded-lg text-[10px] font-extrabold uppercase tracking-widest shadow-sm">Admin</span>`
        : `<span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 border border-indigo-200 dark:bg-indigo-500/10 dark:text-indigo-400 dark:border-indigo-500/20 rounded-lg text-[10px] font-extrabold uppercase tracking-widest shadow-sm">Staff</span>`;

      return `
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors border-b border-gray-50 dark:border-gray-700/50">
          <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-500 font-mono text-xs">#${u.id}</td>
          <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm font-bold text-gray-800 leading-tight">${escapeHtml(u.name)}</div></td>
          <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-600 dark:text-gray-400">${escapeHtml(u.email)}</td>
          <td class="px-6 py-4 whitespace-nowrap">${roleBadge}</td>
          <td class="px-6 py-4 whitespace-nowrap text-center">
            <div class="flex justify-center space-x-1">
              <button onclick="openEdit(${u.id}, '${escapeHtml(u.name)}', '${escapeHtml(u.email)}', '${escapeHtml(u.role)}')" class="p-2 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded-xl transition-all" title="Edit User">
                <i data-feather="edit-3" class="w-4 h-4"></i>
              </button>
              <button onclick="deleteUser(${u.id})" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all" title="Delete User">
                <i data-feather="trash-2" class="w-4 h-4"></i>
              </button>
            </div>
          </td>
        </tr>`;
    }).join('');

    updateUserPaginationControls(allUsers.length);
    feather.replace();
}

// --- Logs Pagination ---
// Tambahkan parameter default dataList = null
function renderLogsPage(dataList = null) {
  const tbody = document.getElementById('logTableBody');
  if (!tbody) return;

  const displayData = dataList || logs;
  const start = (currentLogPage - 1) * logsPerPage;
  const paginatedData = displayData.slice(start, start + logsPerPage);

  if (paginatedData.length === 0) {
    tbody.innerHTML = `<tr><td colspan="6" class="p-10 text-center text-gray-400 italic">No logs found for this period.</td></tr>`;
    return;
  }

  tbody.innerHTML = paginatedData.map(l => {
    // --- Logic Warna Status (Support Dark Mode) ---
    let statusClass = "bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20";
    if (l.status_code >= 500) {
      statusClass = "bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-500/10 dark:text-rose-400 dark:border-rose-500/20";
    } else if (l.status_code >= 400) {
      statusClass = "bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20";
    } else if (l.status_code >= 300) {
      statusClass = "bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20";
    }
    // --- Logic Warna Method (Support Dark Mode) ---
    let methodClass = "text-gray-600 bg-gray-100 dark:bg-gray-700 dark:text-gray-300";
    if (l.method === "POST") {
      methodClass = "text-blue-700 bg-blue-50 dark:bg-blue-900/30 dark:text-blue-400";
    } else if (l.method === "DELETE") {
      methodClass = "text-red-700 bg-red-50 dark:bg-red-900/30 dark:text-red-400";
    }

    return `
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors border-b border-gray-50 dark:border-gray-700/50">
      <td class="px-6 py-4 text-sm font-mono text-gray-800 leading-tight">${l.ip_address}</td>
      <td class="px-6 py-4 text-center"><span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-widest ${methodClass}">${l.method}</span></td>
      <td class="px-6 py-4"><div class="text-blue-600 dark:text-blue-400 font-mono text-xs truncate max-w-[200px]" title="${l.uri}">${l.uri}</div></td>
      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">${humanizePayload(l.payload)}</td>
      <td class="px-6 py-4"><span class="text-xs font-mono text-gray-800 leading-tight">${escapeHtml(l.user_email)}</span></td>
      <td class="px-6 py-4 text-center whitespace-nowrap"><span class="inline-block min-w-[45px] px-2.5 py-1 rounded-lg text-xs font-bold font-mono shadow-sm ${statusClass}">${l.status_code}</span></td>
      <td class="px-6 py-4 text-right text-xs text-gray-400 dark:text-gray-500 font-medium font-mono">${l.created_at}</td>
    </tr>`;
  }).join('');

  // Update info halaman
  const totalPages = Math.ceil(displayData.length / logsPerPage) || 1;
  document.getElementById('logPageInfo').textContent = `Page ${currentLogPage} of ${totalPages} (${displayData.length} entries)`;
  feather.replace();
}
window.nextLogPage  = () => { if(currentLogPage < Math.ceil(logs.length/logsPerPage)) { currentLogPage++; renderLogsPage(); } };
window.prevLogPage  = () => { if(currentLogPage > 1) { currentLogPage--; renderLogsPage(); } };

function updateUserPaginationControls(totalData) {
    const totalPages = Math.ceil(totalData / usersPerPage) || 1;
    // Update teks info
    document.getElementById('userPageInfo').textContent = `Page ${currentUserPage} of ${totalPages} (${totalData} Users)`;

    // Update status tombol
    document.getElementById('btnPrevUser').disabled = (currentUserPage === 1);
    document.getElementById('btnNextUser').disabled = (currentUserPage === totalPages);
}

// Fungsi Navigasi
function nextUserPage() {
  const totalPages = Math.ceil(allUsers.length / usersPerPage);
  if (currentUserPage < totalPages) {
    currentUserPage++;
    renderUserTable();
    window.scrollTo({ top: 0, behavior: 'smooth' }); // Scroll ke atas tabel saat ganti page
  }
}

function prevUserPage() {
  if (currentUserPage > 1) {
    currentUserPage--;
    renderUserTable();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
}

// --- Modals & API Keys ---
window.openGenerateKeyModal = () => {
  document.getElementById('apiKeyModal').classList.remove('hidden');
  document.getElementById('keyFormStep').classList.remove('hidden');
  document.getElementById('keyResultStep').classList.add('hidden');
  document.getElementById('keyLabel').value = "";
  document.getElementById('keyIp').value = "";
  feather.replace();
};

window.copyApiKey = () => {
  const keyInput = document.getElementById('generatedKey');
  keyInput.select();
  document.execCommand('copy');
  Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'success',
    title: 'API Key berhasil disalin!',
    showConfirmButton: false,
    timer: 1500
  });
};

const processGenerateKey = async () => {
  const label     = document.getElementById('keyLabel').value;
  const ip        = document.getElementById('keyIp').value;
  const email     = localStorage.getItem("email");
  const url       = editUserEmail ? `/api/v1/keys/update/${editUserEmail}` : '/api/v1/keys/create';
  const method    = editUserEmail ? 'PUT' : 'POST';
  const body      = { label, ip, email };
  if (!label) {
    Swal.fire('Error', 'Label harus diisi!', 'error');
    return;
  }
  try {
    const response  = await apiFetch(url, {
      method,
      headers : { 'Content-Type': 'application/json' },
      body    : JSON.stringify(body)
    });
    if (response.status === 200) {
      // Tampilkan key asli di Step 2 Modal
      document.getElementById('generatedKey').value = response.key;
      document.getElementById('keyFormStep').classList.add('hidden');
      document.getElementById('keyResultStep').classList.remove('hidden');
      loadApiKeys();
    } else {
      Swal.fire('Gagal', result.message, 'error');
    }
    feather.replace();
  } catch (err) {
    Swal.fire('Gagal', err.message, 'error');
  }
};

window.revokeApiKey = async (id) => {
  // 1. Konfirmasi menggunakan SweetAlert2
  const result = await Swal.fire({
    title: 'Apakah Anda yakin?',
    text: "API Key ini akan langsung mati dan tidak bisa digunakan lagi!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444', // warna merah tailwind
    cancelButtonColor: '#6b7280', // warna abu-abu
    confirmButtonText: 'Ya, Revoke Key!',
    cancelButtonText: 'Batal'
  });
  if (result.isConfirmed) {
    try {
      // 2. Panggil API menggunakan pola apiFetch Anda
      const response = await apiFetch(`/api/v1/keys/delete/${id}`, {
        method: 'DELETE'
      });
      // 3. Jika berhasil (status 200/204), berikan feedback
      Swal.fire('Revoked!', 'API Key telah dinonaktifkan.', 'success');
      // 4. Refresh tabel API Key agar barisnya hilang
      if (typeof loadApiKeys === "function") {
        loadApiKeys();
      }
    } catch (err) {
      // Catch error jika apiFetch melempar error (seperti 404 atau 500)
      Swal.fire('Gagal', err.message, 'error');
    }
  }
};

// -- untuk close modal -- //
window.closeModal = (id) => {
  const el = document.getElementById(id);
  if (el) el.classList.add('hidden');
};

// Menutup modal jika klik di luar box modal (overlay)
window.addEventListener('click', function(e) {
  if (e.target.classList.contains('fixed')) {
    e.target.classList.add('hidden');
    // Opsional: jalankan fungsi reset form jika perlu
  }
});

// --- Modals & CRUD ---
window.openAddStoreModal = () => {
  editStoreId  = null;
  document.getElementById('modalTitle').textContent = "Tambah Store Baru";
  document.getElementById('storeForm').reset();
  document.getElementById('storeModal').classList.remove('hidden');
  feather.replace();
};

const saveStore = async () => {
  const name             = document.getElementById('storeName').value;
  const email            = document.getElementById('storeEmail').value;
  const phone            = document.getElementById('storePhone').value;
  const address          = document.getElementById('storeAddress').value;
  const contact_person   = document.getElementById('storeContactPerson').value;
  const contact_phone    = document.getElementById('storeContactPhone').value;
  const url              = editStoreId ? `/api/v1/store/update/${editStoreId}` : '/api/v1/store/create';
  const method           = editStoreId ? 'PUT' : 'POST';
  const body             = { name, email, phone, address, contact_person, contact_phone };
  try {
    await apiFetch(url, {
      method,
      headers : { 'Content-Type': 'application/json' },
      body    : JSON.stringify(body)
    });
    Swal.fire('Berhasil', 'Data berhasil disimpan', 'success');
    closeModal('storeModal');
    loadStores();
  } catch (err) {
    Swal.fire('Gagal', err.message, 'error');
  }
};

window.openEditStore = (id, name, address, email, phone, contact_person, contact_phone) => {
  editStoreId        = id;
  document.getElementById('modalTitle').textContent   = "Edit Store";
  document.getElementById('storeName').value          = name;
  document.getElementById('storeEmail').value         = email;
  document.getElementById('storePhone').value         = phone;
  document.getElementById('storeAddress').value       = address;
  document.getElementById('storeContactPerson').value = contact_person;
  document.getElementById('storeContactPhone').value  = contact_phone;
  document.getElementById('storeModal').classList.remove('hidden');
  feather.replace();
};

window.deleteStore = async (id) => {
  Swal.fire({
    title: "Yakin ingin hapus?",
    text: "Data store ini akan dihapus permanen!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#ef4444",
    cancelButtonColor: "#6b7280",
    confirmButtonText: "Ya, hapus!",
    cancelButtonText: "Batal"
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        await apiFetch(`/api/v1/store/delete/${id}`, { method: 'DELETE' });
        Swal.fire('Berhasil', 'Store berhasil dihapus', 'success');
        loadStores();
      } catch (err) {
        Swal.fire('Gagal', err.message, 'error');
      }
    }
  });
};

// --- Modals & CRUD ---
window.openAddModal = () => {
  editUserId  = null;
  document.getElementById('modalTitle').textContent = "Tambah User Baru";
  document.getElementById('userForm').reset();
  document.getElementById('userModal').classList.remove('hidden');
  feather.replace();
};

window.openEdit = (id, name, email, role) => {
  editUserId  = id;
  document.getElementById('modalTitle').textContent = "Edit User";
  document.getElementById('userName').value = name;
  document.getElementById('userEmail').value = email;
  document.getElementById('userRole').value = role;
  document.getElementById('userPassword').value = "";
  document.getElementById('userModal').classList.remove('hidden');
  feather.replace();
};

const saveUser = async () => {
  const name      = document.getElementById('userName').value;
  const email     = document.getElementById('userEmail').value;
  const password  = document.getElementById('userPassword').value;
  const role      = document.getElementById('userRole').value;
  const url       = editUserId ? `/api/v1/users/update/${editUserId}` : '/api/v1/users/create';
  const method    = editUserId ? 'PUT' : 'POST';
  const body      = { name, email, role };
  if (password) body.password = password;
  try {
    await apiFetch(url, {
      method,
      headers : { 'Content-Type': 'application/json' },
      body    : JSON.stringify(body)
    });
    Swal.fire('Berhasil', 'Data berhasil disimpan', 'success');
    closeModal('userModal');
    loadUsers();
  } catch (err) {
    Swal.fire('Gagal', err.message, 'error');
  }
};

window.deleteUser = async (id) => {
  const res   = await Swal.fire({ title: 'Hapus user?', icon: 'warning', showCancelButton: true });
  if (res.isConfirmed) {
    try {
      await apiFetch(`/api/v1/users/delete/${id}`, { method: 'DELETE' });
      loadUsers();
    } catch (e) { Swal.fire('Error', 'Gagal hapus', 'error'); }
  }
};

// --- Inisialisasi Saat Halaman Dimuat ---
document.addEventListener('DOMContentLoaded', () => {
    // === Hide menu API Logs jika bukan admin ===
    const userRole = (localStorage.getItem("role") || "").toLowerCase(); // misalnya role disimpan di localStorage
    if (userRole !== "admin") {
      document.getElementById("menuUser")?.style.setProperty("display", "none");
      // document.getElementById("menuAPIKey")?.style.setProperty("display", "none");
    }
    if (userRole === 'staff') {
      document.getElementById('menuUser')?.classList.add('hidden');
    }
    // --- Date Range Logic ---
    const today     = new Date();
    const end       = today.toISOString().split('T')[0];
    const start     = new Date(today);
    start.setDate(today.getDate() - 7);
    const startStr  = start.toISOString().split('T')[0];
    // isi default value ke input date
    document.getElementById('startDate').value    = startStr;
    document.getElementById('endDate').value      = end;
    document.getElementById('logStartDate').value = startStr;
    document.getElementById('logEndDate').value   = end;
    // Tambahkan ini untuk pencarian User
    document.getElementById('searchInput')?.addEventListener('input', (e) => {
        const keyword = e.target.value.toLowerCase();
        const filteredUsers = users.filter(u =>
          u.name.toLowerCase().includes(keyword) ||
          u.role.toLowerCase().includes(keyword) ||
          u.email.toLowerCase().includes(keyword)
        );
        renderUserTable(filteredUsers);
    });
    // Tambahkan ini jika Anda juga ingin pencarian pada API Logs
    document.getElementById('searchLogInput')?.addEventListener('input', (e) => {
        const keyword = e.target.value.toLowerCase();
        // Filter logs
        const filteredLogs = logs.filter(l =>
          (l.ip_address || "").toLowerCase().includes(keyword) ||
          (l.uri || "").toLowerCase().includes(keyword) ||
          (l.method || "").toLowerCase().includes(keyword) ||
          (l.status_code || "").includes(keyword) ||
          (l.user_email || "").toLowerCase().includes(keyword)
        );
        // RESET halaman ke 1 saat mencari
        currentLogPage = 1;
        // Panggil render dengan data hasil filter
        renderLogsPage(filteredLogs);
    });

    // Listener untuk Pencarian API Keys
    document.getElementById('searchApiKeyInput')?.addEventListener('input', (e) => {
      const keyword = e.target.value.toLowerCase();
      filteredApiKeys = apiKeys.filter(k =>
        (k.key_label || "").toLowerCase().includes(keyword) ||
        (k.user_email || "").toLowerCase().includes(keyword) ||
        (k.ip_whitelist || "").toLowerCase().includes(keyword)
      );
      currentApiKeyPage = 1; // Reset ke hal 1 saat mencari
      renderApiKeyTable(filteredApiKeys);
    });

    // Listener untuk Jumlah Baris API Keys
    document.getElementById('apiKeysPerPage')?.addEventListener('change', (e) => {
      apiKeysPerPage    = e.target.value;
      currentApiKeyPage = 1;
      const searchVal   = document.getElementById('searchApiKeyInput').value;
      renderApiKeyTable(searchVal ? filteredApiKeys : apiKeys);
    });
    // Listener untuk perubahan jumlah baris per halaman
    document.getElementById('logsPerPage')?.addEventListener('change', (e) => {
      logsPerPage     = parseInt(e.target.value, 10);
      currentLogPage  = 1;
      renderLogsPage();
    });

    // Jalankan Logout listener
    document.getElementById('logoutBtn')?.addEventListener('click', logout);
    // Simpan user listener
    document.getElementById('saveUserBtn')?.addEventListener('click', saveUser);
    // Simpan store listener
    document.getElementById('saveStoreBtn')?.addEventListener('click', saveStore);
    // Simpan API Keys listener
    document.getElementById('saveApiKeyBtn')?.addEventListener('click', processGenerateKey);
    // LOAD DATA AWAL (Ini yang bikin chart langsung muncul)
    loadDashboardStats();
    loadLogs();
});

// 1. Buat fungsi resize otomatis
const chartResizeObserver = new ResizeObserver(entries => {
  // Kita panggil update pada setiap chart yang ada
  const charts = [window.myChart, window.myChartEmail, window.mychartDaily, window.myChartEmailDaily];
  charts.forEach(chart => {
    if (chart) {
      chart.resize(); // Fungsi bawaan Chart.js untuk menyesuaikan ukuran
    }
  });
});

// 2. Hubungkan ke elemen pembungkus Chart
// Ganti '.grid' dengan ID atau class container chart Anda
const chartContainer = document.querySelector('.grid');
if (chartContainer) {
  chartResizeObserver.observe(chartContainer);
}

// Expose fungsi ke window (Krusial untuk type="module")
window.loadUsers              = loadUsers;
window.loadStores             = loadStores;
window.loadLogs               = loadLogs;
window.loadApiKeys            = loadApiKeys;
window.nextUserPage           = nextUserPage;
window.prevUserPage           = prevUserPage;
window.nextStorePage          = nextStorePage;
window.prevStorePage          = prevStorePage;
window.nextApiKeyPage         = nextApiKeyPage;
window.prevApiKeyPage         = prevApiKeyPage;
window.loadDashboardStats     = loadDashboardStats;
window.bulkApiKeyUpdateStatus = bulkApiKeyUpdateStatus;
window.bulkApiKeyDelete       = bulkApiKeyDelete;
window.clearApiSelection      = clearApiSelection;

window.toggleDarkMode         = () => {
  const html  = document.documentElement;
  const icon  = document.getElementById('darkModeIcon');
  if (html.classList.contains('dark')) {
    html.classList.remove('dark');
    localStorage.setItem('theme', 'light');
    if (icon) icon.setAttribute('data-feather', 'moon');
  } else {
    html.classList.add('dark');
    localStorage.setItem('theme', 'dark');
    if (icon) icon.setAttribute('data-feather', 'sun');
  }
  feather.replace();
    // Render ulang chart agar warna teks legend berubah
  if (typeof loadLogs === 'function') loadLogs();
};

// Fungsi pengecekan saat pertama kali load
const applyTheme = () => {
  const savedTheme  = localStorage.getItem('theme');
  const icon        = document.getElementById('darkModeIcon');
  if (savedTheme === 'dark') {
    document.documentElement.classList.add('dark');
    if (icon) icon.setAttribute('data-feather', 'sun');
  } else {
    document.documentElement.classList.remove('dark');
    if (icon) icon.setAttribute('data-feather', 'moon');
  }
};

// Panggil applyTheme di paling bawah script atau dalam DOMContentLoaded
applyTheme();
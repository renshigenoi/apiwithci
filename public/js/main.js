// Ganti fungsi toggleSidebar lama dengan ini
function toggleSidebar(forceClose = false) {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    if (forceClose) {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    } else {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
}

window.showSection = (id) => {
    // 1. Sembunyikan semua section
    document.querySelectorAll('main section').forEach(s => s.classList.add('hidden'));
    // 2. Tampilkan section target
    const target = document.getElementById(id);
    if (target) {
        target.classList.remove('hidden');
        if (typeof feather !== 'undefined') feather.replace();
    }
    // 3. TUTUP SIDEBAR (Mobile)
    if (window.innerWidth < 1024 && typeof toggleSidebar === 'function') {
        toggleSidebar(true);
    }
    // 4. Update UI Menu Aktif
    document.querySelectorAll('.menu-item').forEach(btn => {
        btn.classList.remove('active', 'bg-gray-700');
    });
    const menuMapping = {
        'dashboardSection': 'menuDashboard',
        'userSection'     : 'menuUser',
        'logsSection'     : 'menuLogs',
        'APIKeySection'   : 'menuAPIKey',
        'storeSection'    : 'menuStore'
    };
    const activeMenu = document.getElementById(menuMapping[id]);
    if (activeMenu) activeMenu.classList.add('active');
    // 5. Load data (Tanpa duplikasi)
    switch (id) {
        case 'dashboardSection':
            if (typeof window.loadDashboardStats === 'function') window.loadDashboardStats();
            if (typeof window.loadLogs === 'function') window.loadLogs();
            break;
        case 'userSection':
            if (typeof window.loadUsers === 'function') window.loadUsers();
            break;
        case 'logsSection':
            if (typeof window.loadLogs === 'function') window.loadLogs();
            break;
        case 'APIKeySection':
            if (typeof window.loadApiKeys === 'function') window.loadApiKeys();
            break;
        case 'storeSection':
            if (typeof window.loadStores === 'function') window.loadStores();
            break;
    }
};

function startLiveClock() {
    const clockElement = document.getElementById('currentDateDisplay');
    // Cek dulu apakah elemennya ada di halaman ini
    if (!clockElement) return;
    const updateClock = () => {
        const now = new Date();
        // Format Tanggal: 23 Apr 2026
        const datePart = now.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        }).replace(/\./g, '');
        // Format Jam: 12:10:05
        const timePart = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        });
        // Tampilan dengan separator vertikal yang tipis
        clockElement.innerHTML = `
            ${datePart}
            <span class="mx-2 opacity-30">|</span>
            <span class="font-mono">${timePart}</span>
        `;
    };
    setInterval(updateClock, 1000);
    updateClock();
}

window.addEventListener('DOMContentLoaded', () => {
    // Tambahkan ini di dalam document.addEventListener('DOMContentLoaded', ...)
    const backToTopBtn = document.getElementById('backToTop');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                // Tampil saat scroll lebih dari 300px
                backToTopBtn.classList.remove('translate-y-20', 'opacity-0');
                backToTopBtn.classList.add('translate-y-0', 'opacity-100');
            } else {
                // Sembunyi
                backToTopBtn.classList.add('translate-y-20', 'opacity-0');
                backToTopBtn.classList.remove('translate-y-0', 'opacity-100');
            }
        });
        backToTopBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    // Set Dashboard sebagai menu aktif pertama kali
    const defaultMenu = document.getElementById('menuDashboard');
    if (defaultMenu) defaultMenu.classList.add('active');
    startLiveClock();
    feather.replace();
});


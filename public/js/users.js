import { apiFetch } from './auth.js';

let editUserId = null;
let masterStores = [];

// --- 1. DATA MASTER TOKO ---

async function loadMasterStores() {
    try {
        const response = await apiFetch('/api/v1/store/options');
        masterStores = response;
    } catch (err) {
        console.error("Gagal memuat master toko:", err);
    }
}

const populateStoreSelect = (selectElement, selectedValue = '') => {
    let options = '<option value="">-- Pilih Toko --</option>';
    masterStores.forEach(store => {
        const isSelected = store.id == selectedValue ? 'selected' : '';
        options += `<option value="${store.id}" ${isSelected}>${store.name} (${store.code})</option>`;
    });
    selectElement.innerHTML = options;
};

// --- 2. LOGIC DINAMIS BARIS TOKO ---

window.addStoreRow = (storeId = '', roleInStore = 'staff', isDefault = false) => {
    const container = document.getElementById('storeListContainer');
    if (!container) return;

    const rowId = 'row-' + Date.now() + Math.floor(Math.random() * 1000);
    const html = `
    <div class="flex items-center gap-2 border dark:border-gray-700 p-3 rounded-xl bg-gray-50 dark:bg-gray-800/50 mb-2" id="${rowId}">
      <div class="flex flex-col items-center px-1" title="Set sebagai default">
        <label class="text-[10px] text-gray-400 mb-1">Def</label>
        <input type="radio" name="default_store" class="default-radio w-4 h-4 cursor-pointer accent-blue-600" ${isDefault ? 'checked' : ''}>
      </div>
      <div class="flex-1">
        <select class="store-select w-full border dark:border-gray-700 dark:bg-gray-800 dark:text-white p-2 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500"></select>
      </div>
      <div class="w-32">
        <select class="role-select w-full border dark:border-gray-700 dark:bg-gray-800 dark:text-white p-2 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500">
          <option value="owner" ${roleInStore === 'owner' ? 'selected' : ''}>Owner</option>
          <option value="manager" ${roleInStore === 'manager' ? 'selected' : ''}>Manager</option>
          <option value="staff" ${roleInStore === 'staff' ? 'selected' : ''}>Staff</option>
        </select>
      </div>
      <button type="button" onclick="document.getElementById('${rowId}').remove()" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors">
        <i data-feather="trash-2" class="w-4 h-4"></i>
      </button>
    </div>`;

    container.insertAdjacentHTML('beforeend', html);
    const newSelect = document.getElementById(rowId).querySelector('.store-select');
    populateStoreSelect(newSelect, storeId);
    feather.replace();
};

// --- 3. CRUD LOGIC ---

window.openAddModal = async () => {
    editUserId = null;
    document.getElementById('modalTitle').textContent = "Tambah User Baru";
    // Reset Form & Container
    document.getElementById('storeUserForm')?.reset();
    document.getElementById('storeListContainer').innerHTML = '';
    // Pastikan Master Store Terisi
    if (masterStores.length === 0) await loadMasterStores();
    // Langsung isi 1 baris akses toko (Set sebagai default otomatis)
    window.addStoreRow('', 'staff', true);
    document.getElementById('storeUserModal').classList.remove('hidden');
    // Reset scroll modal ke atas
    const scrollArea = document.querySelector('#storeUserModal .overflow-y-auto');
    if (scrollArea) scrollArea.scrollTop = 0;
    feather.replace();
};

window.openEdit = async (id, name, email, role) => {
    editUserId = id;
    document.getElementById('modalTitle').textContent = "Edit User";
    document.getElementById('storeUserForm')?.reset();
    document.getElementById('storeUserName').value = name;
    document.getElementById('storeUserEmail').value = email;
    document.getElementById('storeUserRole').value = role;
    const userRole = (localStorage.getItem("role") || "").toLowerCase();
    if (userRole !== "superadmin") {
        document.getElementById('storeManagementArea').classList.add('hidden');
    }
    document.getElementById('storeUserPassword').value = "";
    const container = document.getElementById('storeListContainer');
    container.innerHTML = '<p class="text-sm text-gray-500 animate-pulse">Memuat data akses toko...</p>';
    document.getElementById('storeUserModal').classList.remove('hidden');

    try {
        // PENTING: Pastikan master data sudah ada di memori sebelum render baris
        if (masterStores.length === 0) {
            await loadMasterStores();
        }
        const response = await apiFetch(`/api/v1/users/detail/${id}`);
        container.innerHTML = '';
        if (response.stores && response.stores.length > 0) {
            response.stores.forEach(s => {
                window.addStoreRow(s.store_id, s.role, s.is_default == 1);
            });
        } else {
            window.addStoreRow('', 'staff', true);
        }
    } catch (err) {
        console.error("Error Detail:", err);
        container.innerHTML = '<p class="text-sm text-red-500">Gagal memuat data toko.</p>';
    }
    feather.replace();
};

const saveUser = async () => {
    // Ambil data dari ID yang benar (storeUserName, dll)
    const name     = document.getElementById('storeUserName').value;
    const email    = document.getElementById('storeUserEmail').value;
    const password = document.getElementById('storeUserPassword').value;
    // Ambil data dari baris toko
    const stores = [];
    document.querySelectorAll('#storeListContainer > div').forEach(row => {
        const store_id = row.querySelector('.store-select').value;
        const role_in_store = row.querySelector('.role-select').value;
        const is_default = row.querySelector('.default-radio').checked ? 1 : 0;
        if (store_id) {
            stores.push({ store_id, role_in_store, is_default });
        }
    });

    // Validasi Minimal 1 Default
    if (stores.length > 0 && !stores.some(s => s.is_default === 1)) {
        return Swal.fire('Perhatian', 'Harus ada satu toko yang dipilih sebagai default', 'warning');
    }

    const url = editUserId ? `/api/v1/users/update/${editUserId}` : '/api/v1/users/create';
    const method = editUserId ? 'PUT' : 'POST';
    // Bungkus semua data ke payload (termasuk array stores)
    const payload = {
        name,
        email,
        role: 'user', // Default role untuk toko
        stores
    };

    if (password) payload.password = password;

    try {
        const response = await apiFetch(url, {
            method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        if (response.status === 'success' || response.id) {
            Swal.fire('Berhasil', 'Data berhasil disimpan', 'success');
            closeModal('storeUserModal');
            if (typeof loadUsers === 'function') loadUsers();
        } else {
            const msg = response.messages?.error || response.message || "Gagal menyimpan data";
            Swal.fire('Perhatian', msg, 'warning');
        }
    } catch (err) {
        Swal.fire('Error', err.message || 'Terjadi kesalahan sistem.', 'error');
    }
};

window.deleteUser = async (id) => {
  const res   = await Swal.fire({ title: 'Hapus user?', icon: 'warning', showCancelButton: true });
  if (res.isConfirmed) {
    try {
      const response = await apiFetch(`/api/v1/users/delete/${id}`, {
        method: 'DELETE'
      });
      if (response.status === 'success') {
        Swal.fire('Berhasil', 'User berhasil dihapus', 'success');
      } else {
        const msg = response.messages?.error || response.message || "Gagal menghapus user";
        Swal.fire('Error', msg, 'error');
      }
      loadUsers();
    } catch (e) { Swal.fire('Error', 'Gagal hapus', 'error'); }
  }
};

// --- 4. INITIALIZATION ---

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('saveUserStoreBtn')?.addEventListener('click', saveUser);

    document.getElementById('storeUserRole')?.addEventListener('change', function(e) {
        const area = document.getElementById('storeManagementArea');
        if (e.target.value === 'superadmin') {
            area.classList.add('hidden');
        } else {
            area.classList.remove('hidden');
        }
    });
    // Load Master Store sekali di awal agar saat buka modal tidak lambat
    loadMasterStores();
});

    <div id="storeUserModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-[130] p-4">
      <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-md transform transition-all overflow-hidden border dark:border-gray-800 flex flex-col">
        <div class="p-6 border-b dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50 flex justify-between items-center">
          <h2 id="modalTitle" class="text-xl font-bold text-gray-800 dark:text-white">User Form</h2>
          <button type="button" onclick="closeModal('storeUserModal')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
            <i data-feather="x"></i>
          </button>
        </div>
        <form id="storeUserForm" onsubmit="event.preventDefault();" class="flex flex-col overflow-hidden">
          <div class="p-6 overflow-y-auto" style="max-height: 65vh;">
            <div class="mb-8 border-b dark:border-gray-800 pb-6">
              <h4 class="flex items-center font-bold text-gray-800 dark:text-white mb-4">
                <i data-feather="user" class="w-4 h-4 mr-2"></i> Profil Utama
              </h4>
              <div class="grid grid-cols-1 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap</label>
                  <input type="text" id="storeUserName" name="name" placeholder="Contoh: Albert Einstein" class="w-full border dark:border-gray-700 dark:bg-gray-800 dark:text-white p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                  <input type="email" id="storeUserEmail" name="email" placeholder="nama@email.com" class="w-full border dark:border-gray-700 dark:bg-gray-800 dark:text-white p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role Sistem</label>
                  <select id="storeUserRole" class="w-full border dark:border-gray-700 dark:bg-gray-800 dark:text-white p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    <option value="user">User / Staff</option>
                    <option value="superadmin">Superadmin</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                  <input type="password" id="storeUserPassword" name="password" placeholder="Minimal 6 karakter" class="w-full border dark:border-gray-700 dark:bg-gray-800 dark:text-white p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                  <p class="text-[10px] text-gray-500 mt-1 italic">*Kosongkan jika tidak ingin mengubah (untuk mode edit)</p>
                </div>
              </div>
            </div>
            <div id="storeManagementArea">
              <h4 class="font-bold text-gray-700 dark:text-white mb-4 flex items-center justify-between">
                <span class="flex items-center"><i data-feather="home" class="w-4 h-4 mr-2"></i> Akses Toko</span>
                <button type="button" onclick="addStoreRow()" class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg transition-colors flex items-center">
                  <i data-feather="plus" class="w-3 h-3 mr-1"></i> Tambah
                </button>
              </h4>
              <div id="storeListContainer" class="space-y-3">
              </div>
            </div>
          </div>
          <div class="p-6 border-t dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50 flex gap-3">
            <button type="button" onclick="closeModal('storeUserModal')" class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-700 text-gray-600 dark:text-gray-300 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-all font-semibold">
              Batal
            </button>
            <button type="submit" id="saveUserStoreBtn" class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold shadow-lg shadow-blue-200 dark:shadow-none transition-all active:scale-95">
              Simpan Data
            </button>
          </div>
        </form>
      </div>
    </div>

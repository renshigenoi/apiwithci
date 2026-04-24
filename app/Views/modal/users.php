
    <div id="userModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-[130] p-4">
      <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-md transform transition-all overflow-hidden border dark:border-gray-800">
        <div class="p-6 border-b dark:border-gray-800 flex justify-between items-center">
          <h2 id="modalTitle" class="text-xl font-bold text-gray-800 dark:text-white">User Form</h2>
          <button onclick="closeModal('userModal')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
            <i data-feather="x"></i>
          </button>
        </div>
        <form id="userForm" onsubmit="event.preventDefault();" class="p-6 space-y-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap</label>
            <input type="text" id="userName" placeholder="Contoh: Albert Einstein" class="w-full border dark:border-gray-700 dark:bg-gray-800 dark:text-white p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Email</label>
            <input type="email" id="userEmail" placeholder="nama@email.com" class="w-full border dark:border-gray-700 dark:bg-gray-800 dark:text-white p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Password</label>
            <input type="password" id="userPassword" placeholder="Kosongkan jika tidak ingin mengubah" class="w-full border dark:border-gray-700 dark:bg-gray-800 dark:text-white p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
            <p class="text-[10px] text-gray-500 mt-1">*Minimal 6 karakter untuk user baru</p>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Role</label>
            <select id="userRole" class="w-full border dark:border-gray-700 dark:bg-gray-800 dark:text-white p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
              <option value="admin">Admin</option>
              <option value="staff">Staff</option>
            </select>
          </div>
          <div class="mt-8 flex gap-3">
            <button type="button" onclick="closeModal('userModal')" class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-700 text-gray-600 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-all font-semibold">
              Batal
            </button>
            <button type="button" id="saveUserBtn" class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold shadow-lg shadow-blue-200 dark:shadow-none transition-all active:scale-95">
              Simpan Data
            </button>
          </div>
        </form>
      </div>
    </div>


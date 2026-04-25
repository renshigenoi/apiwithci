
    <div id="storeModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-[130] p-4">
      <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-md transform transition-all overflow-hidden border dark:border-gray-800">
        <div class="p-6 border-b dark:border-gray-800 flex justify-between items-center">
          <h2 id="modalTitle" class="text-xl font-bold text-gray-800 dark:text-white">Store Form</h2>
          <button onclick="closeModal('storeModal')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
            <i data-feather="x"></i>
          </button>
        </div>
        <form id="storeForm" onsubmit="event.preventDefault();" class="p-6 space-y-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Store</label>
            <input type="text" id="storeName" placeholder="Contoh: Albert Einstein" class="w-full border dark:border-gray-700 dark:bg-gray-800 dark:text-white p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Email</label>
            <input type="email" id="storeEmail" placeholder="nama@email.com" class="w-full border dark:border-gray-700 dark:bg-gray-800 dark:text-white p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Phone</label>
            <input type="text" id="storePhone" placeholder="Contoh: 08123456789" class="w-full border dark:border-gray-700 dark:bg-gray-800 dark:text-white p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Address</label>
            <input type="text" id="storeAddress" placeholder="Contoh: Jl. Contoh No. 123" class="w-full border dark:border-gray-700 dark:bg-gray-800 dark:text-white p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Contact Person</label>
            <input type="text" id="storeContactPerson" placeholder="Contoh: Budi Santoso" class="w-full border dark:border-gray-700 dark:bg-gray-800 dark:text-white p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Contact Phone</label>
            <input type="text" id="storeContactPhone" placeholder="Contoh: 08123456789" class="w-full border dark:border-gray-700 dark:bg-gray-800 dark:text-white p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
          </div>
          <div class="mt-8 flex gap-3">
            <button type="button" onclick="closeModal('storeModal')" class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-700 text-gray-600 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-all font-semibold">
              Batal
            </button>
            <button type="button" id="saveStoreBtn" class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold shadow-lg shadow-blue-200 dark:shadow-none transition-all active:scale-95">
              Simpan Data
            </button>
          </div>
        </form>
      </div>
    </div>


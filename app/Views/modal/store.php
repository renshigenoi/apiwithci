
    <div id="storeModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-[130] p-4">
      <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-md transform transition-all overflow-hidden border dark:border-gray-800 flex flex-col">
        <div class="p-6 border-b dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50 flex justify-between items-center">
          <h2 id="modalTitle" class="text-xl font-bold text-gray-800 dark:text-white text-center">Store Form</h2>
          <button type="button" onclick="closeModal('storeModal')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
            <i data-feather="x"></i>
          </button>
        </div>
        <form id="storeForm" onsubmit="event.preventDefault();" class="flex flex-col overflow-hidden">
          <div class="p-6 overflow-y-auto space-y-4" style="max-height: 65vh;">
            <div>
              <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Store Name</label>
              <input type="text" id="storeName" placeholder="Contoh: Toko Maju Jaya" class="w-full border dark:border-gray-700 dark:bg-gray-800 dark:text-white p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Email</label>
                <input type="email" id="storeEmail" placeholder="toko@email.com" class="w-full border dark:border-gray-700 dark:bg-gray-800 dark:text-white p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                <input type="text" id="storePhone" placeholder="0812345..." class="w-full border dark:border-gray-700 dark:bg-gray-800 dark:text-white p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
              </div>
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Address</label>
              <textarea id="storeAddress" rows="2" placeholder="Alamat lengkap store..." class="w-full border dark:border-gray-700 dark:bg-gray-800 dark:text-white p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all resize-none" required></textarea>
            </div>
            <div class="pt-2 border-t dark:border-gray-800">
              <h4 class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest mb-4">Contact Person</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama</label>
                  <input type="text" id="storeContactPerson" placeholder="Nama penanggung jawab" class="w-full border dark:border-gray-700 dark:bg-gray-800 dark:text-white p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                  <input type="text" id="storeContactPhone" placeholder="0812345..." class="w-full border dark:border-gray-700 dark:bg-gray-800 dark:text-white p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
                </div>
              </div>
            </div>
          </div>
          <div class="p-6 border-t dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50 flex gap-3">
            <button type="button" onclick="closeModal('storeModal')" class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-700 text-gray-600 dark:text-gray-300 rounded-xl hover:bg-white dark:hover:bg-gray-800 transition-all font-semibold">
              Batal
            </button>
            <button type="submit" id="saveStoreBtn" class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold shadow-lg shadow-blue-200 dark:shadow-none transition-all active:scale-95">
              Simpan Data
            </button>
          </div>
        </form>
      </div>
    </div>

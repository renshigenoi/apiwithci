
    <div id="apiKeyModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-[100] p-4">
      <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-md transform transition-all overflow-hidden border dark:border-gray-800">
        <div class="p-6 border-b dark:border-gray-800 flex justify-between items-center">
          <h2 id="apiKeyModalTitle" class="text-xl font-bold text-gray-800 dark:text-white">API Key Form</h2>
          <button onclick="closeModal('apiKeyModal')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
            <i data-feather="x"></i>
          </button>
        </div>
        <div class="p-6">
          <div id="keyFormStep" class="space-y-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Key Label</label>
              <input type="text" id="keyLabel" placeholder="Contoh: Apps Monitoring Produksi" class="w-full border dark:border-gray-700 dark:bg-gray-800 dark:text-white p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">IP Whitelist (Opsional)</label>
              <input type="text" id="keyIp" placeholder="192.168.1.1, 10.0.0.1" class="w-full border dark:border-gray-700 dark:bg-gray-800 dark:text-white p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
              <p class="text-[10px] text-gray-500 mt-1">*Pisahkan dengan koma jika lebih dari satu IP</p>
            </div>
            <div class="mt-8 flex gap-3">
              <button type="button" onclick="closeModal('apiKeyModal')" class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-700 text-gray-600 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-all font-semibold">
                Batal
              </button>
              <button type="button" id="saveApiKeyBtn" class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold shadow-lg shadow-blue-200 dark:shadow-none transition-all active:scale-95">
                Generate Key
              </button>
            </div>
          </div>
          <div id="keyResultStep" class="hidden space-y-4">
            <div class="bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-400 p-4 rounded-r-xl">
              <p class="text-xs text-amber-700 dark:text-amber-300 leading-relaxed font-medium">
                <strong>PERHATIAN:</strong> Salin API Key ini sekarang. Demi keamanan, kami hanya menampilkan kunci ini sekali dan tidak dapat dilihat kembali.
              </p>
            </div>
            <div class="relative group">
              <input type="text" id="generatedKey" readonly class="w-full bg-gray-50 dark:bg-gray-800 border dark:border-gray-700 p-4 pr-12 rounded-xl font-mono text-sm text-blue-600 dark:text-blue-400 focus:outline-none">
              <button onclick="copyApiKey()" class="absolute right-3 top-3 p-2 bg-white dark:bg-gray-700 border dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 shadow-sm transition-all" title="Copy to Clipboard">
                <i data-feather="copy" class="w-4 h-4 text-gray-500 dark:text-gray-300"></i>
              </button>
            </div>
            <button type="button" onclick="closeModal('apiKeyModal')" class="w-full mt-6 px-4 py-3 bg-gray-800 dark:bg-gray-700 hover:bg-gray-900 dark:hover:bg-gray-600 text-white rounded-xl font-semibold transition-all">
              Saya Sudah Simpan
            </button>
          </div>
        </div>
      </div>
    </div>


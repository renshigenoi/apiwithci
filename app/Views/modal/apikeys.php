
    <div id="apiKeyModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-[130] p-4">
      <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-md transform transition-all overflow-hidden border dark:border-gray-800 flex flex-col">
        <div class="p-6 border-b dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50 flex justify-between items-center">
          <div class="flex items-center gap-3">
            <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
              <i data-feather="key" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
            </div>
            <h2 id="apiKeyModalTitle" class="text-xl font-bold text-gray-800 dark:text-white">API Key Manager</h2>
          </div>
          <button type="button" onclick="closeModal('apiKeyModal')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
            <i data-feather="x"></i>
          </button>
        </div>
        <div class="p-6 overflow-y-auto" style="max-height: 70vh;">
          <div id="keyFormStep" class="space-y-5">
            <div>
              <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 ml-1">Key Label</label>
              <input type="text" id="keyLabel" placeholder="Contoh: Integrasi Mobile Apps" class="w-full border dark:border-gray-700 dark:bg-gray-800 dark:text-white p-4 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all shadow-sm" required>
              <p class="text-[10px] text-gray-400 mt-2 ml-1 italic">Gunakan nama yang mudah diingat untuk membedakan fungsi key.</p>
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 ml-1">IP Whitelist (Opsional)</label>
              <textarea id="keyIp" placeholder="192.168.1.1, 10.0.0.1" rows="2" class="w-full border dark:border-gray-700 dark:bg-gray-800 dark:text-white p-4 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all resize-none shadow-sm"></textarea>
              <div class="flex items-center gap-1.5 mt-2 ml-1">
                <i data-feather="info" class="w-3 h-3 text-gray-400"></i>
                <p class="text-[10px] text-gray-500 font-medium uppercase tracking-wider">Pisahkan dengan koma</p>
              </div>
            </div>
          </div>
          <div id="keyResultStep" class="hidden space-y-6">
            <div class="flex flex-col items-center text-center space-y-3">
              <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-full flex items-center justify-center shadow-inner">
                <i data-feather="check-circle" class="w-8 h-8"></i>
              </div>
              <h3 class="text-lg font-bold text-gray-800 dark:text-white">API Key Berhasil Dibuat!</h3>
            </div>
            <div class="bg-amber-50 dark:bg-amber-900/30 border-l-4 border-amber-400 p-4 rounded-r-2xl">
              <div class="flex gap-3">
                <i data-feather="alert-triangle" class="w-5 h-5 text-amber-500 flex-shrink-0"></i>
                <p class="text-xs text-amber-800 dark:text-amber-200 leading-relaxed">
                  <strong>PENTING:</strong> Salin key ini sekarang. Kami hanya menampilkannya <strong>sekali ini saja</strong> demi keamanan.
                </p>
              </div>
            </div>
            <div class="space-y-2">
              <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Your Secret API Key</label>
              <div class="flex w-full bg-gray-50 dark:bg-gray-800 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden focus-within:border-blue-500 transition-all shadow-sm">
                <input type="text" id="generatedKey" readonly class="flex-1 bg-transparent p-4 font-mono text-sm text-blue-600 dark:text-blue-400 focus:outline-none tracking-tight min-w-0">
                <button type="button" onclick="copyApiKey()" class="px-5 bg-gray-100 dark:bg-gray-700 hover:bg-blue-50 dark:hover:bg-blue-600 hover:text-blue-600 text-gray-500 transition-all border-l border-gray-200 dark:border-gray-600 flex items-center justify-center" title="Copy to Clipboard">
                  <i data-feather="copy" class="w-5 h-5"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="p-6 border-t dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
          <div id="footerStep1" class="flex gap-3">
            <button type="button" onclick="closeModal('apiKeyModal')" class="flex-1 px-4 py-4 border border-gray-300 dark:border-gray-700 text-gray-600 dark:text-gray-300 rounded-2xl hover:bg-white dark:hover:bg-gray-800 transition-all font-bold">
              Batal
            </button>
            <button type="button" id="saveApiKeyBtn" class="flex-1 px-4 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold shadow-lg shadow-blue-200 dark:shadow-none transition-all active:scale-95">
              Generate Key
            </button>
          </div>
          <div id="footerStep2" class="hidden">
            <button type="button" onclick="closeModal('apiKeyModal')" class="w-full px-4 py-4 bg-gray-900 dark:bg-slate-800 hover:bg-black dark:hover:bg-slate-700 text-white rounded-2xl font-bold transition-all shadow-xl">
              Saya Sudah Simpan & Paham
            </button>
          </div>
        </div>
      </div>
    </div>

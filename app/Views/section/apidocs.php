<section id="docsSection" class="hidden space-y-6">
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
      <div>
        <h2 class="text-lg font-extrabold text-gray-800 dark:text-gray-100">API Documentation</h2>
        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold mt-1">
          Technical guide for integrating with your API services
        </p>
      </div>
    </div>

    <div class="p-6 border-b border-gray-50 bg-gray-50/30 flex flex-col sm:flex-row justify-between gap-4">
      <div class="flex-1 bg-white dark:bg-slate-900 p-4 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm group">
        <div class="flex justify-between items-start mb-3">
          <div>
            <span class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-[0.2em]">Server Environment</span>
            <h4 class="text-xs font-bold mt-0.5 text-gray-700 dark:text-gray-300">Production API v1</h4>
          </div>
        </div>
        <div class="relative group/copy">
          <code id="baseUrlText" class="w-full text-[11px] font-mono text-blue-700 dark:text-blue-300 flex items-center bg-blue-50 dark:bg-slate-900 px-3 py-2.5 rounded-xl border border-blue-100 dark:border-blue-800">
            <i data-feather="globe" class="w-3.5 h-3.5 mr-2 opacity-50"></i>
            <?= base_url('api/v1') ?>
          </code>
        </div>
      </div>

      <div class="flex-1 bg-white dark:bg-slate-900 p-4 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm group">
        <div class="flex justify-between items-start mb-3">
          <div>
            <span class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-[0.2em]">Security Protocol</span>
            <h4 class="text-xs font-bold mt-0.5 text-gray-700 dark:text-gray-300">Header Authentication</h4>
          </div>
          <i data-feather="shield" class="w-4 h-4 text-gray-300 dark:text-gray-500"></i>
        </div>
        <div class="relative group/copy">
          <code id="authHeaderLabel" class="w-full text-[11px] font-mono text-blue-700 dark:text-blue-300 flex items-center bg-blue-50 dark:bg-slate-900 px-3 py-2.5 rounded-xl border border-blue-100 dark:border-blue-800">
            <i data-feather="lock" class="w-3.5 h-3.5 mr-2 opacity-50"></i>
            X-Api-Key: <span class="ml-1 text-gray-400 dark:text-gray-500 font-medium">[your_key]</span>
          </code>
        </div>
      </div>
    </div>

    <div class="p-6">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <div class="space-y-8 text-left">
          <div>
            <div class="flex items-center gap-3 mb-4">
              <span class="px-2.5 py-1 bg-blue-600 text-white text-[10px] font-bold rounded-md uppercase tracking-widest">GET</span>
              <h3 class="text-base font-bold text-gray-800 dark:text-gray-100">Get All Users</h3>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed mb-8">
              Gunakan endpoint ini untuk menarik semua data produk yang terdaftar. Data yang dikembalikan bersifat dinamis sesuai dengan akses store yang dimiliki.
            </p>

            <div class="space-y-4">
              <h4 class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">Required Headers</h4>
              <div class="border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden bg-white dark:bg-slate-900 shadow-sm">
                <div class="p-4 border-b border-gray-50 dark:border-gray-700 flex justify-between items-center bg-gray-50/20">
                  <code class="text-xs font-bold text-blue-600 dark:text-blue-400">X-API-KEY</code>
                  <span class="text-xs font-bold text-blue-600 dark:text-blue-400">Required</span>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-slate-900 overflow-x-auto">
                  <p class="text-sm text-gray-500 dark:text-gray-400 leading-normal font-medium">
                    API Key unik Anda yang didapatkan dari menu API Credentials. Digunakan untuk proses identifikasi akses.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="space-y-6">
          <div class="border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden bg-white dark:bg-slate-900 shadow-sm text-left">
            <div class="p-4 border-b border-gray-50 dark:border-gray-700 flex justify-between items-center bg-gray-50/20">
              <span class="text-xs font-bold text-blue-600 dark:text-blue-400">Example Request</span>
              <button onclick="copyCode('fetchCode')" class="text-gray-400 dark:text-gray-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors cursor-pointer">
                <i data-feather="copy" class="w-3.5 h-3.5"></i>
              </button>
            </div>
            <div class="p-5 bg-gray-50 dark:bg-slate-900 overflow-x-auto">
              <pre id="fetchCode" class="text-sm font-mono leading-relaxed"> <span class="token-key">fetch</span><span class="token-string">('/api/v1/users', {</span>
  <span class="token-key">method</span>: <span class="token-string">'GET'</span><span class="token-string">,</span>
  <span class="token-key">headers</span>: <span class="token-string">{</span>
  <span class="token-key">'Authorization'</span>: <span class="token-string">'Bearer <i>YOUR_JWT_TOKEN</i>'</span>
  <span class="token-key">'X-API-KEY'</span>: <span class="token-string"><i>'YOUR_KEY_HERE'</i></span>
  <span class="token-key">'Content-Type'</span>: <span class="token-string">'application/json'</span>
  <span class="token-string">}</span>
 <span class="token-string">})</span></pre>
            </div>
          </div>

          <div class="border border-gray-100 dark:border-gray-700 rounded-2xl overflow-hidden bg-white shadow-sm text-left">
            <div class="p-4 border-b border-gray-50 dark:border-gray-700 flex justify-between items-center bg-gray-50/20">
              <span class="text-xs font-bold text-blue-600 dark:text-blue-400">Success Response (200)</span>
            </div>
            <div class="p-5 bg-gray-50 dark:bg-slate-900 overflow-x-auto">
              <pre class="text-sm font-mono leading-relaxed"> <span class="token-string">{</span>
  <span class="token-key">"status"</span>: <span class="token-string">"success"</span>,
  <span class="token-key">"message"</span>: <span class="token-string">"Authentication successful"</span>,
  <span class="token-key">"access_token"</span>: <span class="token-string"><i>"ACCESS_TOKKEN"</i></span>,
  <span class="token-key">"refresh_token"</span>: <span class="token-string"><i>"REFRESH_TOKKEN"</i></span>,
  <span class="token-key">"token_type"</span>: <span class="token-string">"Bearer"</span>,
  <span class="token-key">"expires_in"</span>: <span class="token-string">3600</span>,
  <span class="token-key">"user"</span>: <span class="token-string">{</span>
      <span class="token-key">"id"</span>: <span class="token-string"><i>ID</i></span>,
      <span class="token-key">"name"</span>: <span class="token-string"><i>"FULL_NAME"</i></span>,
      <span class="token-key">"email"</span>: <span class="token-string"><i>"EMAIL_ADDRESS"</i></span>,
      <span class="token-key">"role"</span>: <span class="token-string"><i>"ROLE"</i></span>,
  <span class="token-string">}</span></span>
 <span class="token-string">}</span></pre>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="paging-footer p-4 lg:p-6 border-t border-slate-200 dark:border-slate-800 flex items-center gap-2 bg-slate-50 dark:bg-slate-950">
      <i data-feather="help-circle" class="w-4 h-4 text-gray-400 dark:text-gray-500"></i>
      <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">
        Butuh bantuan integrasi? Hubungi tim teknis kami.
      </span>
    </div>
  </div>
</section>

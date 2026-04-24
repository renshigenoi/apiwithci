let accessToken = localStorage.getItem('accessToken');
let refreshToken = localStorage.getItem('refreshToken');

// Ambil token dari localStorage
function getAccessToken() {
  return localStorage.getItem('accessToken');
}
function getRefreshToken() {
  return localStorage.getItem('refreshToken');
}

export async function login(email, password) {
  const res = await fetch('/api/v1/login', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email, password })
  });

  const data = await res.json();

  if (!res.ok || data.status === 'error') {
    throw new Error(data.message);
  }

  localStorage.setItem('accessToken', data.access_token);
  localStorage.setItem('refreshToken', data.refresh_token);
  // simpan role user
  if (data.user && data.user.role) {
    localStorage.setItem('role', data.user.role);
    localStorage.setItem('email', data.user.email);
  }
}

// Fungsi utama untuk request API
export async function apiFetch(url, options = {}) {
  let token = getAccessToken();

  // Tambahkan Authorization header
  options.headers = {
    ...(options.headers || {}),
    'Authorization': `Bearer ${token}`
  };

  let res = await fetch(url, options);

  // Jika token expired (401 Unauthorized)
  if (res.status === 401) {
    const refreshed = await refreshAccessToken();
    if (!refreshed) {
      // Kalau refresh gagal → logout
      logout();
      throw new Error("Session expired, please login again");
    }

    // Retry request dengan token baru
    token = getAccessToken();
    options.headers['Authorization'] = `Bearer ${token}`;
    res = await fetch(url, options);
  }

  const data = await res.json();
  if (!res.ok) {
    throw new Error(data.message || "Request gagal");
  }
  return data;
}

// Refresh token
async function refreshAccessToken() {
  const refreshToken = getRefreshToken();
  if (!refreshToken) return false;

  const res = await fetch('/api/v1/refresh', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ refresh_token: refreshToken })
  });

  if (!res.ok) return false;

  const data = await res.json();
  if (data.status !== 'success') return false;

  localStorage.setItem('accessToken', data.access_token);
  return true;
}

export function logout() {
  localStorage.removeItem('accessToken');
  localStorage.removeItem('refreshToken');
  window.location.href = "/login";
}

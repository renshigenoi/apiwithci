// helper fetch dengan JWT
async function apiFetch(url, options = {}) {
  const token = localStorage.getItem('jwt_token');

  if (!token) {
    // kalau token tidak ada, redirect ke login
    window.location.href = "/login";
    return;
  }

  // tambahkan Authorization header
  options.headers = {
    ...(options.headers || {}),
    'Authorization': 'Bearer ' + token,
    'Content-Type': 'application/json'
  };

  const response = await fetch(url, options);

  // kalau token invalid/expired → redirect ke login
  if (response.status === 401) {
    localStorage.removeItem('jwt_token');
    window.location.href = "/login";
    return;
  }

  return response.json();
}

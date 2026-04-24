import { apiFetch, login } from './auth.js';

// Handler Login Tetap
document.getElementById('loginForm').addEventListener('submit', async e => {
  e.preventDefault();
  const email = document.getElementById('email').value;
  const password = document.getElementById('password').value;

  try {
    await login(email, password);
    window.location.href = "/dashboard";
  } catch (err) {
    Swal.fire({
      title: 'Error',
      text: err.message,
      icon: 'error',
      confirmButtonColor: '#ef4444',
      timer: 2000,
      showConfirmButton: false
    });
  }
});

// Handler Lupa Password - Ditempel ke Window agar bisa diakses HTML
window.handleForgotPassword = async function(e) {
  e.preventDefault();
  const email = document.getElementById('forgotEmail').value;

  Swal.fire({
    title: 'Memproses...',
    text: 'Sedang mengirim instruksi ke email Anda',
    allowOutsideClick: false,
    didOpen: () => { Swal.showLoading() }
  });

  try {
    // Gunakan apiFetch yang sudah diimport
    const response = await apiFetch('/api/v1/forgot-password', {
      method: 'POST',
      body: JSON.stringify({ email }) // Pastikan dikirim sebagai JSON string jika apiFetch butuh itu
    });

    // Sesuaikan pengecekan response sesuai logic apiFetch kamu
    if (response) { 
      Swal.fire({
        icon: 'success',
        title: 'Email Terkirim!',
        text: response.message,
        confirmButtonColor: '#2563eb',
        customClass: { popup: 'rounded-[2rem]', confirmButton: 'rounded-xl px-10 py-3' }
      });
      closeForgotModal(); // Fungsi ini ada di forget.js (global)
    }
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Gagal',
      text: error.message || 'Email tidak terdaftar atau terjadi kesalahan.',
      confirmButtonColor: '#ef4444',
      customClass: { popup: 'rounded-[2rem]', confirmButton: 'rounded-xl px-10 py-3' }
    });
  }
};
// Fungsi Toggle Dark Mode
function toggleDarkMode() {
  const html = document.documentElement;
  if (html.classList.contains('dark')) {
    html.classList.remove('dark');
    localStorage.setItem('theme', 'light');
  } else {
    html.classList.add('dark');
    localStorage.setItem('theme', 'dark');
  }
  updateIcon();
}

function updateIcon() {
  const icon = document.getElementById('themeIcon');
  if (icon) {
    if (document.documentElement.classList.contains('dark')) {
      icon.setAttribute('data-feather', 'sun');
    } else {
      icon.setAttribute('data-feather', 'moon');
    }
    feather.replace();
  }
}

function openForgotModal() {
  document.getElementById('forgotPasswordModal').classList.remove('hidden');
  feather.replace();
}

function closeForgotModal() {
  document.getElementById('forgotEmail').value = '';
  document.getElementById('forgotPasswordModal').classList.add('hidden');
}

// forget.js

document.addEventListener('DOMContentLoaded', () => {
  updateIcon(); // Fungsi yang sudah ada

  // --- LOGIKA RESET PASSWORD ---
  const resetForm = document.getElementById('resetForm');
  if (resetForm) {
    resetForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const token       = document.getElementById('token').value.trim();
      const password    = document.getElementById('newPassword').value.trim();
      const confirm     = document.getElementById('confirmPassword').value.trim();
      if (password !== confirm) {
        return Swal.fire({
          icon  : 'error',
          title : 'Oops...',
          text  : 'Konfirmasi password tidak cocok!',
          confirmButtonColor: '#ef4444'
        });
      }
      Swal.fire({ 
        title   : 'Memproses...',
        icon    : 'info',
        didOpen : () => Swal.showLoading(),
        allowOutsideClick: false 
      });
      try {
        const response  = await fetch('/api/v1/update-password', {
          method      : 'POST',
          headers     : { 'Content-Type': 'application/json' },
          body        : JSON.stringify({
            token     : token,
            password  : password
          })
        });
        const res       = await response.json();
        if (response.ok) {
          Swal.fire({
            icon    : 'success',
            title   : 'Berhasil!',
            text    : 'Password Anda telah diperbarui. Silakan login kembali.',
            confirmButtonColor: '#2563eb'
          }).then(() => window.location.href = '/login');
        } else {
          Swal.fire('Gagal', res.message || 'Token tidak valid.', 'error');
        }
      } catch (err) {
        Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
      }
    });
  }
});
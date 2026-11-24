// BASE_URL berasal dari config.js; notifikasi/flash dari helper.js
const formLogin = document.querySelector('#form-login');
const alertLogin = document.querySelector('#alert-login');
const btnLogin = document.querySelector('#btn-login');

function tampilkanError(pesan) {
    alertLogin.textContent = pesan;
    alertLogin.classList.remove('d-none');
}

formLogin.addEventListener('submit', async (e) => {
    e.preventDefault();
    alertLogin.classList.add('d-none');

    const username = document.querySelector('#username').value.trim();
    const password = document.querySelector('#password').value;

    if (!username || !password) {
        tampilkanError('Username dan password wajib diisi');
        return;
    }

    btnLogin.disabled = true;

    try {
        const res = await fetch(`${BASE_URL}/login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username, password }),
        });
        const hasil = await res.json();

        if (hasil.success) {
            localStorage.setItem('token', hasil.data.token);
            localStorage.setItem('role', hasil.data.role);
            localStorage.setItem('username', username);
            window.location.href = 'dashboard.html';
        } else {
            tampilkanError(hasil.message);
        }
    } catch (error) {
        console.error('Gagal login:', error);
        tampilkanError('Tidak dapat terhubung ke server');
    } finally {
        btnLogin.disabled = false;
    }
});

// Tampilkan notifikasi dari halaman lain (mis. "Registrasi berhasil")
tampilkanFlash();

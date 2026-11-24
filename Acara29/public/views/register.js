// Halaman register.html — POST /register (AuthController::register)
const formRegister = document.querySelector('#form-register');
const btnRegister = document.querySelector('#btn-register');

formRegister.addEventListener('submit', async (e) => {
    e.preventDefault();
    resetError(formRegister);

    const nilai = {
        username: formRegister.elements.username.value.trim(),
        email: formRegister.elements.email.value.trim(),
        password: formRegister.elements.password.value,
        konfirmasi: formRegister.elements.konfirmasi.value,
    };

    // Validasi sisi View (lapisan tambahan di luar validasi Controller)
    const errors = validasiRegister(nilai);
    if (Object.keys(errors).length > 0) {
        tampilkanErrorField(formRegister, errors);
        notifikasi('Periksa kembali isian formulir', 'danger');
        return;
    }

    const labelAwal = btnRegister.innerHTML;
    btnRegister.disabled = true;
    btnRegister.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Mendaftar...';

    try {
        // Konfirmasi password hanya dicek di View, tidak dikirim ke server.
        // auth:false => endpoint publik, tanpa header Authorization.
        const { status, hasil } = await kirim(
            `${BASE_URL}/register`,
            'POST',
            { username: nilai.username, email: nilai.email, password: nilai.password },
            { auth: false }
        );

        if (status === 201) {
            simpanFlash('Registrasi berhasil, silakan login', 'success');
            window.location.href = 'login.html';
            return;
        }

        if (hasil.errors) tampilkanErrorField(formRegister, hasil.errors);
        notifikasi(hasil.message || 'Registrasi gagal', 'danger');
    } catch (error) {
        console.error('Gagal mendaftar:', error);
        notifikasi('Terjadi kesalahan saat mendaftar', 'danger');
    } finally {
        btnRegister.disabled = false;
        btnRegister.innerHTML = labelAwal;
    }
});

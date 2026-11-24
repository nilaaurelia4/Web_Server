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

    btnRegister.disabled = true;
    // Konfirmasi password hanya dicek di View, tidak dikirim ke server.
    // auth:false => endpoint publik, tanpa header Authorization.
    const { status, hasil } = await kirim(
        `${BASE_URL}/register`,
        'POST',
        { username: nilai.username, email: nilai.email, password: nilai.password },
        { auth: false }
    );
    btnRegister.disabled = false;

    if (status === 201) {
        simpanFlash('Registrasi berhasil, silakan login', 'success');
        window.location.href = 'login.html';
        return;
    }

    if (hasil.errors) tampilkanErrorField(formRegister, hasil.errors);
    notifikasi(hasil.message || 'Registrasi gagal', 'danger');
});

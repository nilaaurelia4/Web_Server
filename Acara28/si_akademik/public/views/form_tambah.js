// Halaman form_tambah.html — alur Create (POST /mahasiswa)
wajibAdmin();

const form = document.querySelector('#form-mahasiswa');
const btnSimpan = document.querySelector('#btn-simpan');

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    resetError(form);

    const nilai = {
        nim: form.elements.nim.value.trim(),
        nama: form.elements.nama.value.trim(),
        email: form.elements.email.value.trim(),
        prodi_id: form.elements.prodi_id.value,
        angkatan: form.elements.angkatan.value,
        status: form.elements.status.value,
    };
    const foto = form.elements.foto.files[0];

    // Tugas Mandiri 2: validasi sisi View sebelum data dikirim ke Controller
    const errors = validasiMahasiswa(nilai, foto);
    if (Object.keys(errors).length > 0) {
        tampilkanErrorField(form, errors);
        notifikasi('Periksa kembali isian formulir', 'danger');
        return;
    }

    const formData = new FormData();
    Object.entries(nilai).forEach(([kunci, isi]) => formData.append(kunci, isi));
    if (foto) formData.append('foto', foto);

    btnSimpan.disabled = true;
    const { status, hasil } = await kirim(`${BASE_URL}/mahasiswa`, 'POST', formData);
    btnSimpan.disabled = false;

    if (status === 201 || status === 200) {
        // Tugas Mandiri 1: notifikasi berhasil, ditampilkan di dashboard
        simpanFlash(hasil.message, 'success');
        window.location.href = 'dashboard.html';
        return;
    }

    // Tugas Mandiri 1: notifikasi gagal + pesan error dari Controller per field
    if (hasil.errors) tampilkanErrorField(form, hasil.errors);
    notifikasi(hasil.message || 'Gagal menyimpan data', 'danger');
});

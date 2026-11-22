// Halaman form_edit.html — alur Read (GET ?id=) lalu Update (PUT ?id=)
wajibAdmin();

const form = document.querySelector('#form-mahasiswa');
const btnSimpan = document.querySelector('#btn-simpan');
const fotoPreview = document.querySelector('#foto-preview');

// id dibaca dari query string: form_edit.html?id=5
const id = new URLSearchParams(window.location.search).get('id');

function kembaliKeDashboard(pesan, tipe) {
    simpanFlash(pesan, tipe);
    window.location.href = 'dashboard.html';
}

async function muatDataUntukEdit() {
    if (!id || !/^\d+$/.test(id)) {
        kembaliKeDashboard('ID mahasiswa tidak valid', 'danger');
        return;
    }

    form.querySelectorAll('input, select, button').forEach((el) => { el.disabled = true; });

    // GET /mahasiswa?id=... ditangani MahasiswaController::index()
    const { status, hasil } = await kirim(`${BASE_URL}/mahasiswa?id=${id}`, 'GET', null, { auth: false });

    if (status !== 200 || !hasil.success) {
        kembaliKeDashboard(hasil.message || 'Data tidak ditemukan', 'danger');
        return;
    }

    const d = hasil.data;
    form.elements.nim.value = d.nim;
    form.elements.nama.value = d.nama;
    form.elements.email.value = d.email;
    form.elements.prodi_id.value = d.prodi_id;
    form.elements.angkatan.value = d.angkatan;
    form.elements.status.value = d.status;

    if (d.foto) {
        fotoPreview.src = `${BASE_URL}/uploads/${encodeURIComponent(d.foto)}`;
        fotoPreview.classList.remove('d-none');
    }

    form.querySelectorAll('input, select, button').forEach((el) => { el.disabled = false; });
}

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

    // Tugas Mandiri 2: validasi sisi View
    const errors = validasiMahasiswa(nilai, foto);
    if (Object.keys(errors).length > 0) {
        tampilkanErrorField(form, errors);
        notifikasi('Periksa kembali isian formulir', 'danger');
        return;
    }

    const formData = new FormData();
    Object.entries(nilai).forEach(([kunci, isi]) => formData.append(kunci, isi));
    if (foto) formData.append('foto', foto);

    // PHP hanya mengisi $_POST/$_FILES untuk method POST pada multipart/form-data.
    // Maka update dikirim sebagai POST + _method=PUT, lalu Router meneruskannya
    // ke MahasiswaController::update($id).
    formData.append('_method', 'PUT');

    btnSimpan.disabled = true;
    const { status, hasil } = await kirim(`${BASE_URL}/mahasiswa?id=${id}`, 'POST', formData);
    btnSimpan.disabled = false;

    if (status === 200) {
        kembaliKeDashboard(hasil.message, 'success');   // Tugas Mandiri 1
        return;
    }

    if (hasil.errors) tampilkanErrorField(form, hasil.errors);
    notifikasi(hasil.message || 'Gagal memperbarui data', 'danger');
});

muatDataUntukEdit();

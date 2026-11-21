const BASE_URL = 'http://localhost/Web_Server/Acara26/si-akademik/public';

const token = localStorage.getItem('token');
const role = localStorage.getItem('role');
const username = localStorage.getItem('username');

const tbody = document.querySelector('#tabel-mahasiswa tbody');
const alertDashboard = document.querySelector('#alert-dashboard');
const modal = new bootstrap.Modal(document.querySelector('#modal-mahasiswa'));
const formMhs = document.querySelector('#form-mahasiswa');
const errorForm = document.querySelector('#error-form');

function escapeHtml(teks) {
    return String(teks ?? '').replace(/[&<>"']/g, (c) => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    }[c]));
}

function inisial(nama) {
    return String(nama ?? '?').trim().charAt(0).toUpperCase() || '?';
}

function tampilkanAlert(pesan, tipe = 'danger') {
    alertDashboard.className = `alert alert-${tipe}`;
    alertDashboard.textContent = pesan;
}

// TUGAS MANDIRI 1: Logout
function logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('role');
    localStorage.removeItem('username');
    window.location.href = 'login.html';
}
document.querySelector('#btn-logout').addEventListener('click', logout);

function tampilkanPesanTabel(teks) {
    const kolom = role === 'admin' ? 7 : 6;
    tbody.innerHTML = `<tr><td colspan="${kolom}" class="text-center text-muted py-4">${teks}</td></tr>`;
}

// Request ber-token; 401 (token habis) kembali ke login
async function kirim(url, method, body) {
    const opsi = { method, headers: { 'Authorization': `Bearer ${token}` } };
    if (body instanceof FormData) {
        opsi.body = body;
    } else if (body) {
        opsi.headers['Content-Type'] = 'application/json';
        opsi.body = JSON.stringify(body);
    }
    const res = await fetch(url, opsi);
    const hasil = await res.json();

    if (res.status === 401) {
        alert('Sesi berakhir, silakan login kembali');
        logout();
    }
    return { status: res.status, hasil };
}

// TUGAS MANDIRI 2: sembunyikan tombol untuk role user
function terapkanRole() {
    document.querySelector('#nama-user').textContent = username || 'Pengguna';
    document.querySelector('#badge-role').textContent = role;
    document.querySelector('#info-role').textContent = role;
    document.querySelector('#sapaan-nama').textContent = username || 'pengguna';
    document.querySelector('#avatar-user').textContent = inisial(username);
    document.querySelector('#tanggal').textContent = new Date().toLocaleDateString('id-ID', {
        weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
    });

    if (role !== 'admin') {
        document.querySelectorAll('.aksi-admin').forEach((el) => el.classList.add('d-none'));
    }
}

function badgeStatus(status) {
    return `<span class="pill pill-${escapeHtml(status)}">${escapeHtml(status)}</span>`;
}

// LANGKAH 5: ringkasan data dari MahasiswaController::index()
async function muatData() {
    tampilkanPesanTabel('Memuat data...');

    try {
        const res = await fetch(`${BASE_URL}/mahasiswa`);
        const hasil = await res.json();

        if (!res.ok || !hasil.success) {
            throw new Error(hasil.message || `HTTP ${res.status}`);
        }

        const data = hasil.data || [];
        document.querySelector('#total-mahasiswa').textContent = data.length;
        document.querySelector('#total-aktif').textContent =
            data.filter((m) => m.status === 'aktif').length;

        if (data.length === 0) {
            tampilkanPesanTabel('Belum ada data');
            return;
        }

               tbody.innerHTML = data.map((m) => `
            <tr class="baris-data" data-cari="${escapeHtml(`${m.nim} ${m.nama} ${m.email} ${m.prodi}`.toLowerCase())}">
                <td class="nim">${escapeHtml(m.nim)}</td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        ${m.foto
                            ? `<img class="foto-mhs" src="${BASE_URL}/uploads/${escapeHtml(m.foto)}" alt="">`
                            : `<span class="avatar avatar-sm">${escapeHtml(inisial(m.nama))}</span>`}
                        <span>${escapeHtml(m.nama)}</span>
                    </div>
                </td>
                <td>${escapeHtml(m.email)}</td>
                <td>${escapeHtml(m.prodi)}</td>
                <td>${escapeHtml(m.angkatan)}</td>
                <td>${badgeStatus(m.status)}</td>
                <td class="text-center aksi-admin">
                    <button class="btn btn-sm btn-outline-primary btn-ubah" data-id="${m.id}"><i class="bi bi-pencil-square"></i> Ubah</button>
                    <button class="btn btn-sm btn-outline-danger btn-hapus" data-id="${m.id}"><i class="bi bi-trash"></i> Hapus</button>
                </td>
            </tr>`).join('');

        terapkanRole();
    } catch (error) {
        console.error('Gagal memuat data:', error);
        tampilkanPesanTabel('Gagal memuat data');
    }
}

document.querySelector('#btn-tambah').addEventListener('click', () => {
    formMhs.reset();
    document.querySelector('#mhs-id').value = '';
    document.querySelector('#judul-modal').textContent = 'Tambah Mahasiswa';
    errorForm.classList.add('d-none');
    document.querySelector('#mhs-foto').value = '';
    modal.show();
});

tbody.addEventListener('click', async (e) => {
    const btnUbah = e.target.closest('.btn-ubah');
    const btnHapus = e.target.closest('.btn-hapus');

    if (btnUbah) {
        const res = await fetch(`${BASE_URL}/mahasiswa?id=${btnUbah.dataset.id}`);
        const { data } = await res.json();
        document.querySelector('#mhs-id').value = data.id;
        document.querySelector('#mhs-nim').value = data.nim;
        document.querySelector('#mhs-nama').value = data.nama;
        document.querySelector('#mhs-email').value = data.email;
        document.querySelector('#mhs-prodi').value = data.prodi_id;
        document.querySelector('#mhs-angkatan').value = data.angkatan;
        document.querySelector('#mhs-status').value = data.status;
        document.querySelector('#judul-modal').textContent = 'Ubah Mahasiswa';
        errorForm.classList.add('d-none');
        modal.show();
    }

    if (btnHapus) {
        if (!confirm('Yakin ingin menghapus data ini?')) return;
        const { status, hasil } = await kirim(`${BASE_URL}/mahasiswa?id=${btnHapus.dataset.id}`, 'DELETE');
        tampilkanAlert(hasil.message, hasil.success ? 'success' : 'danger');
        if (status === 200) muatData();
    }
});

formMhs.addEventListener('submit', async (e) => {
    e.preventDefault();
    errorForm.classList.add('d-none');

    const id = document.querySelector('#mhs-id').value;
    const formData = new FormData();
    formData.append('nim', document.querySelector('#mhs-nim').value.trim());
    formData.append('nama', document.querySelector('#mhs-nama').value.trim());
    formData.append('email', document.querySelector('#mhs-email').value.trim());
    formData.append('prodi_id', document.querySelector('#mhs-prodi').value);
    formData.append('angkatan', document.querySelector('#mhs-angkatan').value);
    formData.append('status', document.querySelector('#mhs-status').value);

    const inputFoto = document.querySelector('#mhs-foto');
    if (inputFoto.files[0]) {
        formData.append('foto', inputFoto.files[0]);
    }
    if (id) {
        formData.append('_method', 'PUT');   // untuk Tugas 1
    }

    const url = id ? `${BASE_URL}/mahasiswa?id=${id}` : `${BASE_URL}/mahasiswa`;
    const { status, hasil } = await kirim(url, 'POST', formData);
    if (status === 200 || status === 201) {
        modal.hide();
        tampilkanAlert(hasil.message, 'success');
        muatData();
        return;
    }

    const detail = hasil.errors ? ' - ' + Object.values(hasil.errors).join(', ') : '';
    errorForm.textContent = hasil.message + detail;
    errorForm.classList.remove('d-none');
});

// Pencarian pada tabel
document.querySelector('#cari').addEventListener('input', (e) => {
    const kata = e.target.value.trim().toLowerCase();
    const baris = document.querySelectorAll('#tabel-mahasiswa .baris-data');
    let ditemukan = 0;

    baris.forEach((tr) => {
        const cocok = tr.dataset.cari.includes(kata);
        tr.classList.toggle('d-none', !cocok);
        if (cocok) ditemukan++;
    });

    document.querySelector('#kosong-cari')
        .classList.toggle('d-none', ditemukan > 0 || baris.length === 0);
});

terapkanRole();
muatData();
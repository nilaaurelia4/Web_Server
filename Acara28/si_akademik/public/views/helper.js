// Fungsi bersama untuk semua halaman View (dimuat setelah config.js).
// Catatan: file ini sengaja hanya berisi function, tanpa const/let global,
// agar tidak bentrok dengan variabel di dashboard.js / form_*.js.

function escapeHtml(teks) {
    return String(teks ?? '').replace(/[&<>"']/g, (c) => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    }[c]));
}

function inisial(nama) {
    return String(nama ?? '?').trim().charAt(0).toUpperCase() || '?';
}

/* ---------- Sesi & proteksi halaman ---------- */

function logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('role');
    localStorage.removeItem('username');
    window.location.href = 'login.html';
}

function wajibLogin() {
    if (!localStorage.getItem('token')) {
        window.location.href = 'login.html';
    }
}

// Halaman form_tambah / form_edit hanya untuk admin
function wajibAdmin() {
    wajibLogin();
    if (localStorage.getItem('role') !== 'admin') {
        simpanFlash('Hanya admin yang dapat mengakses halaman tersebut', 'danger');
        window.location.href = 'dashboard.html';
    }
}

/* ---------- Notifikasi visual (Tugas Mandiri 1) ---------- */

function notifikasi(pesan, tipe = 'success') {
    let wadah = document.querySelector('#wadah-notifikasi');
    if (!wadah) {
        wadah = document.createElement('div');
        wadah.id = 'wadah-notifikasi';
        wadah.className = 'position-fixed top-0 end-0 p-3';
        wadah.style.zIndex = 2000;
        document.body.appendChild(wadah);
    }

    const ikon = {
        success: 'bi-check-circle-fill',
        danger: 'bi-x-circle-fill',
        warning: 'bi-exclamation-triangle-fill'
    }[tipe] || 'bi-info-circle-fill';

    const el = document.createElement('div');
    el.className = `toast show align-items-center text-bg-${tipe} border-0 mb-2`;
    el.setAttribute('role', 'alert');
    el.innerHTML = `
        <div class="d-flex">
            <div class="toast-body"><i class="bi ${ikon} me-2"></i><span></span></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" aria-label="Tutup"></button>
        </div>`;
    el.querySelector('span').textContent = pesan;   // textContent: aman dari XSS
    el.querySelector('button').addEventListener('click', () => el.remove());
    wadah.appendChild(el);
    setTimeout(() => el.remove(), 5000);
}

// Notifikasi yang harus tetap tampil setelah pindah halaman
// (mis. "Data berhasil ditambahkan" setelah redirect ke dashboard)
function simpanFlash(pesan, tipe = 'success') {
    sessionStorage.setItem('flash', JSON.stringify({ pesan, tipe }));
}

function tampilkanFlash() {
    const mentah = sessionStorage.getItem('flash');
    if (!mentah) return;
    sessionStorage.removeItem('flash');
    try {
        const { pesan, tipe } = JSON.parse(mentah);
        notifikasi(pesan, tipe);
    } catch (e) { /* abaikan flash yang rusak */ }
}

/* ---------- Request ke API ---------- */

// auth=true  : sertakan header Authorization (dan 401 => paksa login ulang)
// auth=false : untuk login/register (401 di sana berarti salah username/password)
async function kirim(url, method = 'GET', body = null, { auth = true } = {}) {
    const opsi = { method, headers: {} };
    const token = localStorage.getItem('token');

    if (auth && token) {
        opsi.headers['Authorization'] = `Bearer ${token}`;
    }

    if (body instanceof FormData) {
        // JANGAN set Content-Type manual: browser menambahkan boundary multipart otomatis
        opsi.body = body;
    } else if (body) {
        opsi.headers['Content-Type'] = 'application/json';
        opsi.body = JSON.stringify(body);
    }

    let res;
    try {
        res = await fetch(url, opsi);
    } catch (error) {
        console.error('Gagal terhubung:', error);
        return { status: 0, hasil: { success: false, message: 'Tidak dapat terhubung ke server' } };
    }

    let hasil;
    try {
        hasil = await res.json();
    } catch (error) {
        hasil = { success: false, message: `Respons server tidak valid (HTTP ${res.status})` };
    }

    if (auth && res.status === 401) {
        simpanFlash('Sesi berakhir, silakan login kembali', 'warning');
        logout();
    }
    return { status: res.status, hasil };
}

/* ---------- Validasi sisi View (Tugas Mandiri 2) ---------- */

const POLA_EMAIL = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

function resetError(form) {
    form.querySelectorAll('.is-invalid').forEach((el) => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback').forEach((el) => { el.textContent = ''; });
}

// errors = { namaField: 'pesan' }, namaField = atribut name pada input
function tampilkanErrorField(form, errors) {
    let pertama = null;
    Object.entries(errors).forEach(([nama, pesan]) => {
        const input = form.elements[nama];
        if (!input) return;
        input.classList.add('is-invalid');
        const fb = input.parentElement.querySelector('.invalid-feedback');
        if (fb) fb.textContent = pesan;
        if (!pertama) pertama = input;
    });
    if (pertama) pertama.focus();
}

function validasiMahasiswa(d, fotoFile) {
    const e = {};

    if (!d.nim) e.nim = 'NIM wajib diisi';
    else if (!/^\d{6}$/.test(d.nim)) e.nim = 'NIM harus 6 digit angka';

    if (!d.nama) e.nama = 'Nama wajib diisi';

    if (!d.email) e.email = 'Email wajib diisi';
    else if (!POLA_EMAIL.test(d.email)) e.email = 'Format email tidak valid';

    if (!d.prodi_id) e.prodi_id = 'Program studi wajib dipilih';

    if (!d.angkatan) e.angkatan = 'Angkatan wajib diisi';
    else if (Number(d.angkatan) < 1901 || Number(d.angkatan) > 2155) {
        e.angkatan = 'Angkatan harus berupa tahun antara 1901 dan 2155';
    }

    if (!d.status) e.status = 'Status wajib dipilih';

    if (fotoFile) {
        if (!['image/jpeg', 'image/png'].includes(fotoFile.type)) {
            e.foto = 'Foto harus berformat JPG atau PNG';
        } else if (fotoFile.size > 2 * 1024 * 1024) {
            e.foto = 'Ukuran foto maksimal 2MB';
        }
    }
    return e;
}

function validasiRegister(d) {
    const e = {};

    if (!d.username) e.username = 'Username wajib diisi';
    else if (d.username.length < 3 || d.username.length > 50) e.username = 'Username 3–50 karakter';
    else if (/\s/.test(d.username)) e.username = 'Username tidak boleh mengandung spasi';

    if (!d.email) e.email = 'Email wajib diisi';
    else if (!POLA_EMAIL.test(d.email)) e.email = 'Format email tidak valid';

    if (!d.password) e.password = 'Password wajib diisi';
    else if (d.password.length < 6) e.password = 'Password minimal 6 karakter';

    if (!d.konfirmasi) e.konfirmasi = 'Konfirmasi password wajib diisi';
    else if (d.konfirmasi !== d.password) e.konfirmasi = 'Konfirmasi password tidak sama';

    return e;
}

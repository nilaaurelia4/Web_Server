// Dashboard admin: hanya Read (tabel) dan Delete.
// Create dan Update dipindah ke form_tambah.html dan form_edit.html.
// BASE_URL berasal dari config.js; fungsi bersama dari helper.js.

const role = localStorage.getItem('role');
const username = localStorage.getItem('username');
const tbody = document.querySelector('#tabel-mahasiswa tbody');

document.querySelector('#btn-logout').addEventListener('click', logout);

function tampilkanPesanTabel(teks) {
    const kolom = role === 'admin' ? 7 : 6;
    tbody.innerHTML = `<tr><td colspan="${kolom}" class="text-center text-muted py-4">${teks}</td></tr>`;
}

// Sembunyikan elemen khusus admin untuk role user
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

// Data diambil lewat fetch dari MahasiswaController::index(), sehingga
// setiap kembali dari form tabel diperbarui tanpa render ulang di server.
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
                            ? `<img class="foto-mhs" src="${BASE_URL}/uploads/${encodeURIComponent(m.foto)}" alt="">`
                            : `<span class="avatar avatar-sm">${escapeHtml(inisial(m.nama))}</span>`}
                        <span>${escapeHtml(m.nama)}</span>
                    </div>
                </td>
                <td>${escapeHtml(m.email)}</td>
                <td>${escapeHtml(m.prodi)}</td>
                <td>${escapeHtml(m.angkatan)}</td>
                <td>${badgeStatus(m.status)}</td>
                <td class="text-center aksi-admin">
                    <a class="btn btn-sm btn-outline-primary btn-ubah" href="form_edit.html?id=${encodeURIComponent(m.id)}"><i class="bi bi-pencil-square"></i> Ubah</a>
                    <button class="btn btn-sm btn-outline-danger btn-hapus" data-id="${escapeHtml(m.id)}"><i class="bi bi-trash"></i> Hapus</button>
                </td>
            </tr>`).join('');

        terapkanRole();
    } catch (error) {
        console.error('Gagal memuat data:', error);
        tampilkanPesanTabel('Gagal memuat data');
        notifikasi('Gagal memuat data mahasiswa', 'danger');
    }
}

// Delete: DELETE /mahasiswa?id=... ditangani MahasiswaController::destroy($id)
async function hapusData(id) {
    if (!confirm('Yakin ingin menghapus data ini?')) return;

    const tombol = Array.from(tbody.querySelectorAll('.btn-hapus'))
        .find((el) => el.dataset.id === String(id));
    const labelAwal = tombol ? tombol.innerHTML : '';
    if (tombol) {
        tombol.disabled = true;
        tombol.innerHTML = '<span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>Menghapus...';
    }

    try {
        const { status, hasil } = await kirim(`${BASE_URL}/mahasiswa?id=${encodeURIComponent(id)}`, 'DELETE');

        if (status === 200) {
            notifikasi(hasil.message || 'Data berhasil dihapus', 'success');
            await muatData();
        } else {
            notifikasi(hasil.message || 'Gagal menghapus data', 'danger');
        }
    } catch (error) {
        console.error('Gagal menghapus data mahasiswa:', error);
        notifikasi('Terjadi kesalahan saat menghapus data mahasiswa', 'danger');
    } finally {
        if (tombol) {
            tombol.disabled = false;
            tombol.innerHTML = labelAwal;
        }
    }
}

tbody.addEventListener('click', (e) => {
    const btnHapus = e.target.closest('.btn-hapus');
    if (btnHapus) hapusData(btnHapus.dataset.id);
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
tampilkanFlash();   // notifikasi dari form_tambah / form_edit
muatData();

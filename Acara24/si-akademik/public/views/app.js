const BASE_URL = 'http://localhost/Web_Server/Acara24/si-akademik/public';

// Mencegah karakter HTML dari data dieksekusi sebagai tag
function escapeHtml(teks) {
    return String(teks ?? '').replace(/[&<>"']/g, (c) => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    }[c]));
}

function tampilkanPesan(tbody, teks) {
    tbody.innerHTML = `<tr><td colspan="3">${teks}</td></tr>`;
}

async function muatDataMahasiswa() {
    const tbody = document.querySelector('#tabel-mahasiswa tbody');

    // Tugas Mandiri 1: indikator sebelum data berhasil diambil
    tampilkanPesan(tbody, 'Memuat data...');

    try {
        const response = await fetch(`${BASE_URL}/mahasiswa`);
        const hasil = await response.json();

        if (!response.ok || !hasil.success) {
            throw new Error(hasil.message || `HTTP ${response.status}`);
        }

        // Tugas Mandiri 2: penanganan data kosong
        if (!hasil.data || hasil.data.length === 0) {
            tampilkanPesan(tbody, 'Belum ada data');
            return;
        }

        tbody.innerHTML = '';
        hasil.data.forEach((mhs) => {
            tbody.innerHTML += `
                <tr>
                    <td>${escapeHtml(mhs.nim)}</td>
                    <td>${escapeHtml(mhs.nama)}</td>
                    <td>${escapeHtml(mhs.prodi)}</td>
                </tr>`;
        });
    } catch (error) {
        console.error('Gagal memuat data:', error);
        tampilkanPesan(tbody, 'Gagal memuat data');
    }
}

document.addEventListener('DOMContentLoaded', muatDataMahasiswa);
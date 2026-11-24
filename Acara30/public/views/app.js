const BASE_URL = `${window.location.origin}${window.location.pathname
    .replace(/\/views(?:\/.*)?$/, '')}`;

// Mencegah karakter HTML dari data dieksekusi sebagai tag
function escapeHtml(teks) {
    return String(teks ?? '').replace(/[&<>"']/g, (c) => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    }[c]));
}

function tampilkanPesan(tbody, teks) {
    const baris = document.createElement('tr');
    const sel = document.createElement('td');
    sel.colSpan = 3;
    sel.textContent = teks;
    baris.appendChild(sel);
    tbody.replaceChildren(baris);
}

function renderTabel(data) {
    const tbody = document.querySelector('#tabel-mahasiswa tbody');
    tbody.innerHTML = data.map((mhs) => `
        <tr>
            <td>${escapeHtml(mhs.nim)}</td>
            <td>${escapeHtml(mhs.nama)}</td>
            <td>${escapeHtml(mhs.prodi)}</td>
        </tr>`).join('');
}

async function muatDataMahasiswa() {
    const tbody = document.querySelector('#tabel-mahasiswa tbody');

    // Tugas Mandiri 1: indikator sebelum data berhasil diambil
    tampilkanPesan(tbody, 'Memuat data...');

    try {
        const response = await fetch(`${BASE_URL}/mahasiswa`);
        const hasil = await response.json();

        if (!response.ok || !hasil.success) {
            throw new Error(hasil.message || `Server merespons dengan status ${response.status}`);
        }

        // Tugas Mandiri 2: penanganan data kosong
        if (!hasil.data || hasil.data.length === 0) {
            tampilkanPesan(tbody, 'Belum ada data');
            return;
        }

        renderTabel(hasil.data);
    } catch (error) {
        console.error('Gagal memuat data:', error);
        tampilkanPesan(tbody, `Gagal memuat data: ${error.message}`);
    }
}

document.addEventListener('DOMContentLoaded', muatDataMahasiswa);
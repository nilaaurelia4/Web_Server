# Test Case API si-akademik

Metode: Manual Testing (Postman) dengan teknik Blackbox Testing, Equivalence Partitioning.
Cakupan: AuthController (`/register`, `/login`) dan MahasiswaController (`/mahasiswa`).

Catatan data uji:
- `{{base_url}}` = alamat folder `public` project, `{{token}}` = token hasil login (berlaku 5 menit).
- NIM wajib 6 digit angka (aturan validasi Acara 18), jadi data uji memakai NIM 6 digit.
- `{id_baru}` = id yang dikembalikan TC 01 (perkiraan 14, sesuai AUTO_INCREMENT database).
- Data awal database: NIM 251002 milik id 1, id prodi yang ada 1, 2, 3 dan 4 (id 5 tidak ada).

## A. Test Case Prosedur Kerja

| ID | Skenario (Partisi) | Endpoint | Data Uji | Hasil Diharapkan | Hasil Aktual | Status |
|----|--------------------|----------|----------|------------------|--------------|--------|
| TC 01 | NIM valid dan lengkap (valid) | POST /mahasiswa + token | nim=`220123`, nama, email valid, prodi_id ada, angkatan=2022 | 201 Created | 201 Created | Pass |
| TC 02 | NIM kosong (tidak valid) | POST /mahasiswa + token | nim=`""`, field lain valid | 400 Bad Request | 400 Bad Request | Pass |
| TC 03 | NIM duplikat (tidak valid) | POST /mahasiswa + token | nim=`220123` (sudah dipakai TC 01) | 422 Unprocessable Entity | 409 Conflict | Fail |
| TC 04 | Login kredensial benar (valid) | POST /login | username & password benar | 200 OK, token diterima | 200 OK, token diterima | Pass |
| TC 05 | Login password salah (tidak valid) | POST /login | username benar, password salah | 401 Unauthorized | 401 Unauthorized | Pass |
| TC 06 | Akses store tanpa token (tidak valid) | POST /mahasiswa | tanpa header Authorization | 401 Unauthorized | 401 Unauthorized | Pass |

## B. Test Case Tugas Mandiri (update dan destroy)

| ID | Skenario (Partisi) | Endpoint | Data Uji | Hasil Diharapkan | Hasil Aktual | Status |
|----|--------------------|----------|----------|------------------|--------------|--------|
| TC 07 | Update data valid, id ada (valid) | PUT /mahasiswa?id={id_baru} + token | nama diganti, field lain valid | 200 OK | 200 OK | Pass |
| TC 08 | Update id tidak ada (tidak valid) | PUT /mahasiswa?id=99999 + token | body valid | 404 Not Found | 404 Not Found | Pass |
| TC 09 | Update tanpa token (tidak valid) | PUT /mahasiswa?id={id_baru} | body valid, tanpa Authorization | 401 Unauthorized | 401 Unauthorized | Pass|
| TC 10 | Update NIM bukan 6 digit (tidak valid) | PUT /mahasiswa?id={id_baru} + token | nim=`12345` | 422 Unprocessable Entity | 422 Unprocessable Entity | Pass |
| TC 11 | Update NIM milik mahasiswa lain (tidak valid) | PUT /mahasiswa?id={id_baru} + token | nim=`251002` (milik id 1) | 422 Unprocessable Entity | 422 Unprocessable Entity | Pass |
| TC 12 | Update format email salah (tidak valid) | PUT /mahasiswa?id={id_baru} + token | email=`budi.example.com` | 422 Unprocessable Entity | 422 Unprocessable Entity | Pass |
| TC 13 | Hapus data dengan id ada (valid) | DELETE /mahasiswa?id={id_baru} + token | id dari TC 01 | 200 OK | 200 OK | Pass |
| TC 14 | Hapus id tidak ada (tidak valid) | DELETE /mahasiswa?id=99999 + token | id tidak ada | 404 Not Found | 404 Not Found | Pass |
| TC 15 | Hapus tanpa token (tidak valid) | DELETE /mahasiswa?id={id_baru} | tanpa Authorization | 401 Unauthorized | 401 Unauthorized | Pass |
| TC 16 | Hapus id bukan angka (tidak valid) | DELETE /mahasiswa?id=abc + token | id=`abc` | 400 Bad Request | 400 Bad Request | Pass |
| TC 17 | Update prodi_id tidak ada (tidak valid) | PUT /mahasiswa?id={id_baru} + token | prodi_id=`5` | 422 Unprocessable Entity | 422 Unprocessable Entity | Pass |
| TC 18 | Update angkatan di luar rentang tahun (tidak valid) | PUT /mahasiswa?id={id_baru} + token | angkatan=`3000` | 422 Unprocessable Entity | 422 Unprocessable Entity | Pass |
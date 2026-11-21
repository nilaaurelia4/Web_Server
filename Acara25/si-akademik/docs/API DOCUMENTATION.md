# Dokumentasi API si-akademik

API RESTful untuk data mahasiswa dan autentikasi pengguna, dibangun dengan PHP Native (pola MVC, Router, Front Controller) dan database MySQL `si_akademik`.

**Base URL:** `http://localhost/Web_Server/Acara21/si-akademik/public` 

**Format response (seluruh endpoint):**

```json
{
  "success": true,
  "message": "Pesan hasil proses",
  "data": null,
  "errors": { "field": "keterangan error (hanya muncul jika ada)" },
  "timestamp": "2026-10-30T10:00:00+07:00"
}
```

---

## 1. Ringkasan Endpoint

| No | Method | URL | Controller::Method | Autentikasi | Keterangan |
|----|--------|-----|--------------------|-------------|------------|
| 1 | POST | `/register` | AuthController::register | Publik | Pendaftaran pengguna baru |
| 2 | POST | `/login` | AuthController::login | Publik | Autentikasi pengguna, menghasilkan token |
| 3 | GET | `/mahasiswa` | MahasiswaController::index | Publik | Menampilkan seluruh data mahasiswa |
| 4 | GET | `/mahasiswa?id={id}` | MahasiswaController::index | Publik | Menampilkan satu mahasiswa berdasarkan ID |
| 5 | POST | `/mahasiswa` | MahasiswaController::store | Terproteksi (Bearer Token) | Menambah data mahasiswa |
| 6 | PUT | `/mahasiswa?id={id}` | MahasiswaController::update | Terproteksi (Bearer Token) | Mengubah data mahasiswa |
| 7 | DELETE | `/mahasiswa?id={id}` | MahasiswaController::destroy | Terproteksi (Bearer Token) | Menghapus data mahasiswa |
| 8 | GET | `/buku` | BukuController::index | Publik | Menampilkan data buku (Acara 17) |
| 9 | GET | `/buku?id={id}` | BukuController::index | Publik | Menampilkan satu buku berdasarkan ID |
| 10 | POST | `/buku` | BukuController::store | Publik | Menambah data buku |
| 11 | PUT | `/buku?id={id}` | BukuController::update | Publik | Mengubah data buku |
| 12 | DELETE | `/buku?id={id}` | BukuController::destroy | Publik | Menghapus data buku |

## 2. Peta Router

| Resource (URL) | Controller | Keterangan |
|----------------|------------|------------|
| `/mahasiswa` | MahasiswaController | CRUD data mahasiswa (index/store/update/destroy) |
| `/buku` | BukuController | CRUD data buku (index/store/update/destroy) |
| `/login` | AuthController::login | Autentikasi pengguna, menghasilkan token |
| `/register` | AuthController::register | Pendaftaran pengguna baru |

Pemetaan method HTTP pada resource CRUD: `GET` → `index`, `POST` → `store`, `PUT` → `update`, `DELETE` → `destroy`. ID dikirim melalui query string (`?id=1`). Resource yang tidak terdaftar menghasilkan **404**, dan method yang tidak didukung menghasilkan **405**.

## 3. Kode Status yang Digunakan

| Kode | Arti | Contoh penyebab |
|------|------|-----------------|
| 200 | OK | Data berhasil diambil / diperbarui / dihapus, login berhasil |
| 201 | Created | Registrasi atau penambahan data berhasil |
| 400 | Bad Request | Field wajib belum lengkap, ID tidak diisi |
| 401 | Unauthorized | Token tidak ada, tidak valid, kedaluwarsa, atau login salah |
| 404 | Not Found | Endpoint atau data tidak ditemukan |
| 405 | Method Not Allowed | Method HTTP tidak diizinkan pada endpoint tersebut |
| 409 | Conflict | NIM sudah terdaftar |
| 422 | Unprocessable Entity | Data lengkap tetapi gagal validasi (format NIM, email, dsb.) |
| 500 | Internal Server Error | Kesalahan database / server |

---

## 4. Endpoint Autentikasi

### POST /register

Controller: `AuthController::register()`  
Autentikasi: Publik

**Headers:**

```
Content-Type: application/json
```

**Request Body:**

| Field | Tipe | Wajib | Keterangan |
|-------|------|-------|------------|
| username | string | Ya | Nama pengguna |
| email | string | Ya | Harus berformat email yang valid |
| password | string | Ya | Disimpan dalam bentuk hash |

```json
{
  "username": "admin",
  "email": "admin@example.com",
  "password": "rahasia123"
}
```

**Response 201 Created:**

```json
{
  "success": true,
  "message": "Registrasi berhasil",
  "data": { "id": 1 },
  "timestamp": "2026-10-30T10:00:00+07:00"
}
```

**Response 400 Bad Request** (ada field kosong):

```json
{
  "success": false,
  "message": "Username, email, dan password wajib diisi",
  "data": null,
  "timestamp": "2026-10-30T10:00:00+07:00"
}
```

**Response 422 Unprocessable Entity** (format email salah):

```json
{
  "success": false,
  "message": "Validasi data gagal",
  "data": null,
  "errors": { "email": "Format email tidak valid" },
  "timestamp": "2026-10-30T10:00:00+07:00"
}
```

**Response 405 Method Not Allowed** (selain POST):

```json
{
  "success": false,
  "message": "Method tidak diizinkan",
  "data": null,
  "timestamp": "2026-10-30T10:00:00+07:00"
}
```

---

### POST /login

Controller: `AuthController::login()`  
Autentikasi: Publik

**Headers:**

```
Content-Type: application/json
```

**Request Body:**

| Field | Tipe | Wajib | Keterangan |
|-------|------|-------|------------|
| username | string | Ya | Nama pengguna terdaftar |
| password | string | Ya | Password pengguna |

```json
{
  "username": "admin",
  "password": "rahasia123"
}
```

**Response 200 OK:**

```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "token": "eyJhbGciOiJIUzI1NiIs...",
    "role": "admin"
  },
  "timestamp": "2026-10-30T10:00:00+07:00"
}
```

Token berupa JWT (HS256) dengan masa berlaku **5 menit**. Gunakan token pada header `Authorization: Bearer <token>`.

**Response 401 Unauthorized** (username atau password salah):

```json
{
  "success": false,
  "message": "Username atau password salah",
  "data": null,
  "timestamp": "2026-10-30T10:00:00+07:00"
}
```

---

## 5. Endpoint Mahasiswa

Struktur data mahasiswa:

| Field | Tipe | Wajib | Keterangan |
|-------|------|-------|------------|
| nim | string | Ya | Harus 6 digit angka dan unik |
| nama | string | Ya | Nama mahasiswa |
| email | string | Ya | Harus berformat email yang valid |
| prodi_id | integer | Ya | Harus ada pada tabel `prodi` |
| angkatan | integer | Ya | Tahun angkatan |
| status | string | Tidak | `aktif`, `cuti`, atau `lulus` (default `aktif`) |

### GET /mahasiswa

Controller: `MahasiswaController::index()`  
Autentikasi: Publik  
Headers: tidak ada yang wajib.

**Response 200 OK:**

```json
{
  "success": true,
  "message": "Data berhasil diambil",
  "data": [
    {
      "id": 1,
      "nim": "230001",
      "nama": "Nila Agustin",
      "email": "nila@example.com",
      "prodi_id": 1,
      "prodi": "Teknik Informatika",
      "angkatan": 2023,
      "status": "aktif"
    }
  ],
  "timestamp": "2026-10-30T10:00:00+07:00"
}
```

### GET /mahasiswa?id={id}

Controller: `MahasiswaController::index()`  
Autentikasi: Publik

**Response 200 OK:** sama seperti di atas, tetapi `data` berisi satu objek mahasiswa.

**Response 404 Not Found:**

```json
{
  "success": false,
  "message": "Data tidak ditemukan",
  "data": null,
  "timestamp": "2026-10-30T10:00:00+07:00"
}
```

---

### POST /mahasiswa

Controller: `MahasiswaController::store()`  
Autentikasi: **Terproteksi** (`Auth::requireAuth()`)

**Headers:**

```
Content-Type: application/json
Authorization: Bearer <token>
```

**Request Body:**

```json
{
  "nim": "230001",
  "nama": "Nila Agustin",
  "email": "nila@example.com",
  "prodi_id": 1,
  "angkatan": 2023,
  "status": "aktif"
}
```

**Response 201 Created:**

```json
{
  "success": true,
  "message": "Data berhasil ditambahkan",
  "data": { "id": 1 },
  "timestamp": "2026-10-30T10:00:00+07:00"
}
```

**Response 400 Bad Request** (field wajib belum lengkap):

```json
{
  "success": false,
  "message": "Field wajib belum lengkap",
  "data": null,
  "errors": { "nama": "Field nama wajib diisi" },
  "timestamp": "2026-10-30T10:00:00+07:00"
}
```

**Response 401 Unauthorized** (token tidak dikirim):

```json
{
  "success": false,
  "message": "Token tidak ditemukan",
  "data": null,
  "timestamp": "2026-10-30T10:00:00+07:00"
}
```

Token salah atau kedaluwarsa menghasilkan 401 dengan pesan `Token tidak valid atau kedaluwarsa`.

**Response 409 Conflict** (NIM sudah terdaftar):

```json
{
  "success": false,
  "message": "NIM sudah terdaftar",
  "data": null,
  "errors": { "nim": "NIM sudah terdaftar" },
  "timestamp": "2026-10-30T10:00:00+07:00"
}
```

**Response 422 Unprocessable Entity** (validasi gagal):

```json
{
  "success": false,
  "message": "Validasi data gagal",
  "data": null,
  "errors": { "nim": "Format NIM tidak valid, harus 6 digit angka" },
  "timestamp": "2026-10-30T10:00:00+07:00"
}
```

Kemungkinan isi `errors` pada 422: `nim` (bukan 6 digit angka), `email` (format salah), `status` (selain aktif/cuti/lulus), `prodi_id` (tidak ditemukan).

---

### PUT /mahasiswa?id={id}

Controller: `MahasiswaController::update()`  
Autentikasi: **Terproteksi** (`Auth::requireAuth()`)

**Headers:**

```
Content-Type: application/json
Authorization: Bearer <token>
```

**Request Body:** sama seperti `POST /mahasiswa` (seluruh field wajib dikirim ulang).

**Response 200 OK:**

```json
{
  "success": true,
  "message": "Data berhasil diperbarui",
  "data": null,
  "timestamp": "2026-10-30T10:00:00+07:00"
}
```

**Response gagal:**

| Kode | Penyebab | Pesan |
|------|----------|-------|
| 400 | ID kosong / bukan angka, atau field wajib belum lengkap | `ID wajib diisi` / `Field wajib belum lengkap` |
| 401 | Token tidak ada / tidak valid | `Token tidak ditemukan` / `Token tidak valid atau kedaluwarsa` |
| 404 | ID tidak ada di database | `Data tidak ditemukan` |
| 409 | NIM dipakai mahasiswa lain | `NIM sudah dipakai mahasiswa lain` |
| 422 | Validasi data gagal | `Validasi data gagal` |

---

### DELETE /mahasiswa?id={id}

Controller: `MahasiswaController::destroy()`  
Autentikasi: **Terproteksi** (`Auth::requireAuth()`)

**Headers:**

```
Authorization: Bearer <token>
```

**Response 200 OK:**

```json
{
  "success": true,
  "message": "Data berhasil dihapus",
  "data": null,
  "timestamp": "2026-10-30T10:00:00+07:00"
}
```

**Response gagal:**

| Kode | Penyebab | Pesan |
|------|----------|-------|
| 400 | ID kosong / bukan angka | `ID wajib diisi` |
| 401 | Token tidak ada / tidak valid | `Token tidak ditemukan` / `Token tidak valid atau kedaluwarsa` |
| 404 | ID tidak ada di database | `Data tidak ditemukan` |
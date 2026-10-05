<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Mahasiswa - SI Akademik</title>

    <!-- Bootstrap 5 CDN -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background-color: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background-color: #0b1f3a;
        }

        .navbar-brand {
            font-weight: bold;
            color: white !important;
        }

        .nav-link {
            color: white !important;
        }

        .nav-link:hover {
            color: #d1d5db !important;
        }

        .content {
            padding: 35px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            background-color: #0b1f3a;
            color: white;
            font-weight: bold;
            padding: 15px 20px;
            border-radius: 10px 10px 0 0 !important;
        }

        .form-label {
            font-weight: 600;
        }

        .btn-primary {
            background-color: #0b1f3a;
            border-color: #0b1f3a;
        }

        .btn-primary:hover {
            background-color: #08172b;
            border-color: #08172b;
        }

        .btn-secondary {
            border-radius: 6px;
        }

        @media (max-width: 768px) {
            .content {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

<?php
    $basePath = dirname($_SERVER['SCRIPT_NAME'] ?? '/');
    $basePath = $basePath === '.' ? '' : $basePath;
?>

<!-- Content -->
<div class="container-fluid content">

    <div class="row justify-content-center">

        <div class="col-lg-8 col-md-10">

            <div class="card">

                <div class="card-header">
                    Edit Data Mahasiswa
                </div>

                <div class="card-body p-4">

                    <form action="<?= htmlspecialchars($basePath . '/mahasiswa/' . $id . '/edit') ?>" method="POST">

                        <!-- NIM -->
                        <div class="mb-3">
                            <label for="nim" class="form-label">
                                NIM
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="nim"
                                name="nim"
                                value="<?= htmlspecialchars($mhs->getNim()) ?>"
                                required
                            >
                        </div>

                        <!-- Nama -->
                        <div class="mb-3">
                            <label for="nama" class="form-label">
                                Nama Mahasiswa
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="nama"
                                name="nama"
                                value="<?= htmlspecialchars($mhs->getNama()) ?>"
                                required
                            >
                        </div>

                        <!-- Prodi -->
                        <div class="mb-3">
                            <label for="prodi" class="form-label">
                                Prodi
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="prodi"
                                name="prodi"
                                value="<?= htmlspecialchars($mhs->getProdi()) ?>"
                                required
                            >
                        </div>

                        <!-- Button -->
                        <div class="d-flex justify-content-end gap-2">

                            <a href="<?= htmlspecialchars($basePath . '/mahasiswa') ?>"
                               class="btn btn-secondary">
                                Kembali
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary">
                                Simpan Perubahan
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- Bootstrap JS -->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>
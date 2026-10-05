<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Mahasiswa</title>

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

        .table th {
            background-color: #0b1f3a;
            color: white;
            vertical-align: middle;
        }

        .table td {
            vertical-align: middle;
        }

        .btn-primary {
            background-color: #0b1f3a;
            border-color: #0b1f3a;
        }

        .btn-primary:hover {
            background-color: #08172b;
            border-color: #08172b;
        }

        .btn {
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

<!-- Content -->
<div class="container-fluid content">

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <span>
                Data Mahasiswa
            </span>

            <a href="create.php" class="btn btn-light btn-sm">
                + Tambah Mahasiswa
            </a>

        </div>


        <div class="card-body">

            <!-- Table responsive -->
            <div class="table-responsive">

                <table class="table table-bordered table-hover mb-0">

                    <thead>
                        <tr>
                            <th width="5%" class="text-center">
                                No
                            </th>

                            <th>
                                NIM
                            </th>

                            <th>
                                Nama
                            </th>

                            <th>
                                Prodi
                            </th>

                            <th width="20%" class="text-center">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <td class="text-center">
                                1
                            </td>

                            <td>
                                230101001
                            </td>

                            <td>
                                Andi Pratama
                            </td>

                            <td>
                                Teknik Informatika
                            </td>

                            <td class="text-center">

                                <a href="#"
                                   class="btn btn-info btn-sm text-white">
                                    Detail
                                </a>

                                <a href="#"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <a href="#"
                                   class="btn btn-danger btn-sm">
                                    Hapus
                                </a>

                            </td>
                        </tr>


                        <tr>
                            <td class="text-center">
                                2
                            </td>

                            <td>
                                230101002
                            </td>

                            <td>
                                Budi Santoso
                            </td>

                            <td>
                                Sistem Informasi
                            </td>

                            <td class="text-center">

                                <a href="#"
                                   class="btn btn-info btn-sm text-white">
                                    Detail
                                </a>

                                <a href="#"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <a href="#"
                                   class="btn btn-danger btn-sm">
                                    Hapus
                                </a>

                            </td>
                        </tr>


                        <tr>
                            <td class="text-center">
                                3
                            </td>

                            <td>
                                230101003
                            </td>

                            <td>
                                Citra Lestari
                            </td>

                            <td>
                                Teknik Komputer
                            </td>

                            <td class="text-center">

                                <a href="#"
                                   class="btn btn-info btn-sm text-white">
                                    Detail
                                </a>

                                <a href="#"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <a href="#"
                                   class="btn btn-danger btn-sm">
                                    Hapus
                                </a>

                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>




</body>
</html>
```

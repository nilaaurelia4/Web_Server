<?php

ob_start();

?>

<div class="container-fluid content">

    <div class="row justify-content-center">

        <div class="col-lg-8 col-md-10">

            <div class="card">

                <div class="card-header">
                    Tambah Data Mahasiswa
                </div>

                <div class="card-body p-4">

                    <form action="#" method="POST">

                        <div class="mb-3">

                            <label for="nim" class="form-label">
                                NIM
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="nim"
                                name="nim"
                                placeholder="Masukkan NIM"
                                required
                            >

                        </div>

                        <div class="mb-3">

                            <label for="nama" class="form-label">
                                Nama Mahasiswa
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="nama"
                                name="nama"
                                placeholder="Masukkan nama mahasiswa"
                                required
                            >

                        </div>

                        <div class="mb-3">

                            <label for="prodi" class="form-label">
                                Prodi
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="prodi"
                                name="prodi"
                                placeholder="Masukkan prodi"
                                required
                            >

                        </div>

                        <div class="d-flex justify-content-end gap-2">

                            <a href="../../../public/"
                               class="btn btn-secondary">
                                Kembali
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary">
                                Simpan
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<?php

$content = ob_get_clean();

$title = 'Tambah Mahasiswa - SI Akademik';

include __DIR__ . '/../layouts/main.php';

?>
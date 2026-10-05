<?php

ob_start();

?>

<div class="container-fluid content">

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <span>
                Data Mahasiswa
            </span>

            <a href="../app/Views/mahasiswa/create.php"
               class="btn btn-light btn-sm">

                + Tambah Mahasiswa

            </a>

        </div>


        <div class="card-body">

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

                            <th>
                                Angkatan
                            </th>

                            <th width="20%" class="text-center">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php foreach ($mahasiswa as $index => $mhs): ?>

                            <tr>

                                <td class="text-center">
                                    <?= $index + 1 ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars($mhs->getNim()) ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars($mhs->getNama()) ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars($mhs->getProdi()) ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars($mhs->getAngkatan()) ?>
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

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<?php

$content = ob_get_clean();

$title = 'Data Mahasiswa - SI Akademik';

include __DIR__ . '/../layouts/main.php';

?>
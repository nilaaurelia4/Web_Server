<?php

ob_start();

$basePath = dirname($_SERVER['SCRIPT_NAME'] ?? '/');
$basePath = $basePath === '.' ? '' : $basePath;

?>

<div class="container-fluid content">

 

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <span>
                Data Mahasiswa
            </span>

            <a href="<?= htmlspecialchars($basePath . '/mahasiswa/create') ?>"
            class="btn btn-info">
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

                                    <a href="<?= htmlspecialchars($basePath . '/mahasiswa/' . ($index + 1)) ?>"
                                       class="btn btn-info btn-sm text-white">

                                        Detail

                                    </a>


                                    <a href="<?= htmlspecialchars($basePath . '/mahasiswa/' . ($index + 1) . '/edit') ?>"
                                    class="btn btn-warning btn-sm">
                                        Edit
                                    </a>


                                    <form action="<?= htmlspecialchars($basePath . '/mahasiswa/' . ($index + 1) . '/delete') ?>"
                                          method="POST"
                                          class="d-inline">
                                        <button type="submit"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin ingin menghapus data mahasiswa ini?')">
                                            Hapus
                                        </button>
                                    </form>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

                    <div class="mt-4">
                        <a href="<?= htmlspecialchars($basePath . '/dashboard') ?>"
                        class="btn btn-info btn-sm">
                            &larr; Kembali ke Dashboard
                        </a>
                    </div>

            </div>

        </div>

    </div>

</div>

<?php

$content = ob_get_clean();

$title = 'Data Mahasiswa - SI Akademik';

include __DIR__ . '/../layouts/main.php';

?>
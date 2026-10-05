<?php

ob_start();

$basePath = dirname(
    $_SERVER['SCRIPT_NAME'] ?? '/'
);

$basePath = $basePath === '.'
    ? ''
    : $basePath;

?>

<div class="container-fluid content">

    <div class="card shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                Data Mahasiswa
            </h5>

            <a
                href="<?= htmlspecialchars(
                    $basePath . '/mahasiswa/create'
                ) ?>"
                class="btn btn-primary"
            >
                + Tambah Mahasiswa
            </a>

        </div>


        <div class="card-body">

            <!-- TABEL MAHASISWA -->

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th class="text-center">
                                No
                            </th>

                            <th>
                                NIM
                            </th>

                            <th>
                                Nama
                            </th>

                            <th>
                                Email
                            </th>

                            <th>
                                Program Studi
                            </th>

                            <th>
                                Angkatan
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-center">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php if (empty($mahasiswa)): ?>

                        <tr>

                            <td
                                colspan="8"
                                class="text-center"
                            >
                                Data mahasiswa tidak ditemukan.
                            </td>

                        </tr>

                    <?php else: ?>


                        <?php foreach (
                            $mahasiswa as $index => $mhs
                        ): ?>

                            <?php

                            $statusClass = match (
                                $mhs['status']
                            ) {
                                'aktif' => 'success',
                                'cuti' => 'warning',
                                'lulus' => 'primary',
                                default => 'secondary'
                            };

                            ?>


                            <tr>

                                <td class="text-center">

                                    <?= $index + 1 ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $mhs['nim']
                                    ) ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $mhs['nama']
                                    ) ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $mhs['email']
                                    ) ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $mhs['prodi_kode']
                                        . ' - '
                                        . $mhs['prodi_nama']
                                    ) ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $mhs['angkatan']
                                    ) ?>

                                </td>


                                <td>

                                    <span
                                        class="badge text-bg-<?= $statusClass ?>"
                                    >

                                        <?= htmlspecialchars(
                                            ucfirst(
                                                $mhs['status']
                                            )
                                        ) ?>

                                    </span>

                                </td>


                                <td class="text-center">

                                    <a
                                        href="<?= htmlspecialchars(
                                            $basePath
                                            . '/mahasiswa/'
                                            . $mhs['id']
                                        ) ?>"
                                        class="btn btn-info btn-sm text-white"
                                    >
                                        Detail
                                    </a>


                                    <a
                                        href="<?= htmlspecialchars(
                                            $basePath
                                            . '/mahasiswa/'
                                            . $mhs['id']
                                            . '/edit'
                                        ) ?>"
                                        class="btn btn-warning btn-sm"
                                    >
                                        Edit
                                    </a>


                                    <form
                                        action="<?= htmlspecialchars(
                                            $basePath
                                            . '/mahasiswa/'
                                            . $mhs['id']
                                            . '/delete'
                                        ) ?>"
                                        method="POST"
                                        class="d-inline"
                                    >

                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus data mahasiswa ini?')"
                                        >
                                            Hapus
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>


            <div class="mt-3">

                <a
                    href="<?= htmlspecialchars(
                        $basePath . '/dashboard'
                    ) ?>"
                    class="btn btn-secondary"
                >
                    ← Kembali ke Dashboard
                </a>

            </div>

        </div>

    </div>

</div>


<?php

$content = ob_get_clean();

$title = 'Data Mahasiswa - SI Akademik';

include __DIR__ . '/../layouts/main.php';

?>
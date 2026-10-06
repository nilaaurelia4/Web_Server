<?php

ob_start();

$basePath = dirname(
    $_SERVER['SCRIPT_NAME'] ?? '/'
);

$basePath = $basePath === '.'
    ? ''
    : $basePath;

$statusClass = match ($mhs['status']) {

    'aktif' => 'success',

    'cuti' => 'warning',

    'lulus' => 'primary',

    default => 'secondary'
};

?>

<div class="container-fluid content">

    <div class="card shadow-sm">

        <div class="card-header">

            <h5 class="mb-0">
                Detail Mahasiswa
            </h5>

        </div>


        <div class="card-body">

            <table class="table table-bordered">

                <tr>

                    <th width="30%">
                        NIM
                    </th>

                    <td>
                        <?= htmlspecialchars(
                            $mhs['nim']
                        ) ?>
                    </td>

                </tr>


                <tr>

                    <th>
                        Nama
                    </th>

                    <td>
                        <?= htmlspecialchars(
                            $mhs['nama']
                        ) ?>
                    </td>

                </tr>


                <tr>

                    <th>
                        Email
                    </th>

                    <td>
                        <?= htmlspecialchars(
                            $mhs['email']
                        ) ?>
                    </td>

                </tr>


                <tr>

                    <th>
                        Program Studi
                    </th>

                    <td>

                        <?= htmlspecialchars(
                            $mhs['prodi_kode']
                            . ' - '
                            . $mhs['prodi_nama']
                        ) ?>

                    </td>

                </tr>


                <tr>

                    <th>
                        Angkatan
                    </th>

                    <td>
                        <?= htmlspecialchars(
                            $mhs['angkatan']
                        ) ?>
                    </td>

                </tr>


                <tr>

                    <th>
                        Status
                    </th>

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

                </tr>




                <tr>

                    <th>
                        Diperbarui
                    </th>

                    <td>
                        <?= htmlspecialchars(
                            $mhs['updated_at']
                        ) ?>
                    </td>

                </tr>

            </table>


            <div class="mt-3">

                <a
                    href="<?= htmlspecialchars(
                        $basePath . '/mahasiswa'
                    ) ?>"
                    class="btn btn-secondary"
                >
                    Kembali
                </a>


                <a
                    href="<?= htmlspecialchars(
                        $basePath
                        . '/mahasiswa/'
                        . $mhs['id']
                        . '/edit'
                    ) ?>"
                    class="btn btn-warning"
                >
                    Edit
                </a>

            </div>

        </div>

    </div>

</div>


<?php

$content = ob_get_clean();

$title = 'Detail Mahasiswa - SI Akademik';

include __DIR__ . '/../layouts/main.php';

?>
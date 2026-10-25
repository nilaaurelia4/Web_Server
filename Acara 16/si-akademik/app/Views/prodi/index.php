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
                Data Program Studi
            </h5>

            <a
                href="<?= htmlspecialchars(
                    $basePath . '/prodi/create'
                ) ?>"
                class="btn btn-primary"
            >
                + Tambah Prodi
            </a>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">

                        <tr>

                            <th width="70" class="text-center">
                                No
                            </th>

                            <th>
                                Kode
                            </th>

                            <th>
                                Nama Program Studi
                            </th>

                            <th width="220" class="text-center">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php if (empty($prodi)): ?>

                        <tr>

                            <td
                                colspan="4"
                                class="text-center"
                            >
                                Belum ada data prodi.
                            </td>

                        </tr>

                    <?php else: ?>

                        <?php foreach (
                            $prodi as $index => $p
                        ): ?>

                            <tr>

                                <td class="text-center">

                                    <?= (
                                        (($page - 1) * $perPage)
                                        + $index
                                        + 1
                                    ) ?>

                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $p['kode']
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $p['nama']
                                    ) ?>
                                </td>

                                <td class="text-center">

                                    <a
                                        href="<?= htmlspecialchars(
                                            $basePath
                                            . '/prodi/'
                                            . $p['id']
                                            . '/edit'
                                        ) ?>"
                                        class="btn btn-warning btn-sm"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="<?= htmlspecialchars(
                                            $basePath
                                            . '/prodi/'
                                            . $p['id']
                                            . '/delete'
                                        ) ?>"
                                        method="POST"
                                        class="d-inline"
                                    >

                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus prodi ini?')"
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

            <?php if ($totalPages > 1): ?>

                <nav class="mt-4">

                    <ul class="pagination justify-content-center">

                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">

                            <a
                                class="page-link"
                                href="<?= htmlspecialchars(
                                    $basePath
                                    . '/prodi?page='
                                    . ($page - 1)
                                ) ?>"
                            >
                                Previous
                            </a>

                        </li>

                        <?php for (
                            $i = 1;
                            $i <= $totalPages;
                            $i++
                        ): ?>

                            <li
                                class="page-item <?= $i === $page ? 'active' : '' ?>"
                            >

                                <a
                                    class="page-link"
                                    href="<?= htmlspecialchars(
                                        $basePath
                                        . '/prodi?page='
                                        . $i
                                    ) ?>"
                                >
                                    <?= $i ?>
                                </a>

                            </li>

                        <?php endfor; ?>

                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">

                            <a
                                class="page-link"
                                href="<?= htmlspecialchars(
                                    $basePath
                                    . '/prodi?page='
                                    . ($page + 1)
                                ) ?>"
                            >
                                Next
                            </a>

                        </li>

                    </ul>

                </nav>

            <?php endif; ?>

            <div class="text-center text-muted mb-3">

                Menampilkan
                <?= count($prodi) ?>
                dari
                <?= $total ?>
                data prodi.

            </div>

            <a
                href="<?= htmlspecialchars(
                    $basePath . '/dashboard'
                ) ?>"
                class="btn btn-secondary"
            >
                ← Dashboard
            </a>

        </div>

    </div>

</div>

<?php

$content = ob_get_clean();

$title = 'Data Prodi - SI Akademik';

include __DIR__ . '/../layouts/main.php';

?>
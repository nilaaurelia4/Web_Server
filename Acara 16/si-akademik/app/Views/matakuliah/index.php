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
                Data Mata Kuliah
            </h5>

            <a
                href="<?= htmlspecialchars(
                    $basePath . '/matakuliah/create'
                ) ?>"
                class="btn btn-primary"
            >
                + Tambah Mata Kuliah
            </a>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th width="70" class="text-center">
                                No
                            </th>

                            <th>
                                Kode
                            </th>

                            <th>
                                Nama Mata Kuliah
                            </th>

                            <th>
                                SKS
                            </th>

                            <th>
                                Program Studi
                            </th>

                            <th width="220" class="text-center">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php if (empty($matakuliah)): ?>

                        <tr>

                            <td
                                colspan="6"
                                class="text-center"
                            >
                                Belum ada data mata kuliah.
                            </td>

                        </tr>

                    <?php else: ?>

                        <?php foreach (
                            $matakuliah as $index => $mk
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
                                        $mk['kode']
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $mk['nama']
                                    ) ?>
                                </td>

                                <td class="text-center">
                                    <?= htmlspecialchars(
                                        $mk['sks']
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $mk['prodi_kode']
                                        . ' - '
                                        . $mk['prodi_nama']
                                    ) ?>
                                </td>

                                <td class="text-center">

                                    <a
                                        href="<?= htmlspecialchars(
                                            $basePath
                                            . '/matakuliah/'
                                            . $mk['id']
                                            . '/edit'
                                        ) ?>"
                                        class="btn btn-warning btn-sm"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="<?= htmlspecialchars(
                                            $basePath
                                            . '/matakuliah/'
                                            . $mk['id']
                                            . '/delete'
                                        ) ?>"
                                        method="POST"
                                        class="d-inline"
                                    >

                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus mata kuliah ini?')"
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
                                    . '/matakuliah?page='
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
                                        . '/matakuliah?page='
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
                                    . '/matakuliah?page='
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
                <?= count($matakuliah) ?>
                dari
                <?= $total ?>
                data mata kuliah.

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

$title = 'Data Mata Kuliah - SI Akademik';

include __DIR__ . '/../layouts/main.php';

?>
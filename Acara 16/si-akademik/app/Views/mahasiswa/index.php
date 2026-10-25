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

            <!-- SEARCH -->

            <form
                method="GET"
                action="<?= htmlspecialchars(
                    $basePath . '/mahasiswa'
                ) ?>"
                class="row g-2 mb-4"
            >

                <div class="col-md-10">

                    <input
                        type="text"
                        name="q"
                        class="form-control"
                        value="<?= htmlspecialchars(
                            $keyword ?? ''
                        ) ?>"
                        placeholder="Cari berdasarkan NIM atau nama..."
                    >

                </div>

                <div class="col-md-2 d-grid">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Cari
                    </button>

                </div>

            </form>

            <!-- TABLE -->

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

                            $nomor =
                                (($page - 1) * $perPage)
                                + $index
                                + 1;

                            ?>

                            <tr>

                                <td class="text-center">
                                    <?= $nomor ?>
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

            <!-- PAGINATION -->

            <?php if ($totalPages > 1): ?>

                <nav class="mt-4">

                    <ul class="pagination justify-content-center">

                        <!-- PREVIOUS -->

                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">

                            <a
                                class="page-link"
                                href="<?= htmlspecialchars(
                                    $basePath
                                    . '/mahasiswa?page='
                                    . ($page - 1)
                                    . '&q='
                                    . urlencode($keyword)
                                ) ?>"
                            >
                                Previous
                            </a>

                        </li>

                        <!-- NOMOR HALAMAN -->

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
                                        . '/mahasiswa?page='
                                        . $i
                                        . '&q='
                                        . urlencode($keyword)
                                    ) ?>"
                                >
                                    <?= $i ?>
                                </a>

                            </li>

                        <?php endfor; ?>

                        <!-- NEXT -->

                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">

                            <a
                                class="page-link"
                                href="<?= htmlspecialchars(
                                    $basePath
                                    . '/mahasiswa?page='
                                    . ($page + 1)
                                    . '&q='
                                    . urlencode($keyword)
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
                <?= count($mahasiswa) ?>
                dari
                <?= $total ?>
                data mahasiswa.

            </div>

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

<?php

$content = ob_get_clean();

$title = 'Data Mahasiswa - SI Akademik';

include __DIR__ . '/../layouts/main.php';

?>
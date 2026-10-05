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

    <div class="row justify-content-center">

        <div class="col-lg-8 col-md-10">

            <div class="card shadow-sm">

                <div class="card-header">

                    <h5 class="mb-0">
                        Edit Data Mahasiswa
                    </h5>

                </div>


                <div class="card-body p-4">

                    <form
                        action="<?= htmlspecialchars(
                            $basePath
                            . '/mahasiswa/'
                            . $mhs['id']
                            . '/edit'
                        ) ?>"
                        method="POST"
                    >

                        <!-- NIM -->

                        <div class="mb-3">

                            <label class="form-label">
                                NIM
                            </label>

                            <input
                                type="text"
                                name="nim"
                                class="form-control"
                                maxlength="20"
                                value="<?= htmlspecialchars(
                                    $mhs['nim']
                                ) ?>"
                                required
                            >

                        </div>


                        <!-- NAMA -->

                        <div class="mb-3">

                            <label class="form-label">
                                Nama Mahasiswa
                            </label>

                            <input
                                type="text"
                                name="nama"
                                class="form-control"
                                maxlength="100"
                                value="<?= htmlspecialchars(
                                    $mhs['nama']
                                ) ?>"
                                required
                            >

                        </div>


                        <!-- EMAIL -->

                        <div class="mb-3">

                            <label class="form-label">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                maxlength="100"
                                value="<?= htmlspecialchars(
                                    $mhs['email']
                                ) ?>"
                                required
                            >

                        </div>


                        <!-- PRODI -->

                        <div class="mb-3">

                            <label class="form-label">
                                Program Studi
                            </label>

                            <select
                                name="prodi_id"
                                class="form-select"
                                required
                            >

                                <?php foreach ($prodi as $p): ?>

                                    <option
                                        value="<?= $p['id'] ?>"
                                        <?= (int) $mhs['prodi_id']
                                            === (int) $p['id']
                                            ? 'selected'
                                            : '' ?>
                                    >

                                        <?= htmlspecialchars(
                                            $p['kode']
                                            . ' - '
                                            . $p['nama']
                                        ) ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <!-- ANGKATAN -->

                        <div class="mb-3">

                            <label class="form-label">
                                Angkatan
                            </label>

                            <input
                                type="number"
                                name="angkatan"
                                class="form-control"
                                min="2000"
                                max="2100"
                                value="<?= htmlspecialchars(
                                    $mhs['angkatan']
                                ) ?>"
                                required
                            >

                        </div>


                        <!-- STATUS -->

                        <div class="mb-3">

                            <label class="form-label">
                                Status Mahasiswa
                            </label>

                            <select
                                name="status"
                                class="form-select"
                                required
                            >

                                <option
                                    value="aktif"
                                    <?= $mhs['status'] === 'aktif'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    Aktif
                                </option>

                                <option
                                    value="cuti"
                                    <?= $mhs['status'] === 'cuti'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    Cuti
                                </option>

                                <option
                                    value="lulus"
                                    <?= $mhs['status'] === 'lulus'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    Lulus
                                </option>

                            </select>

                        </div>


                        <!-- BUTTON -->

                        <div
                            class="d-flex justify-content-end gap-2"
                        >

                            <a
                                href="<?= htmlspecialchars(
                                    $basePath . '/mahasiswa'
                                ) ?>"
                                class="btn btn-secondary"
                            >
                                Kembali
                            </a>


                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Simpan Perubahan
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

$title = 'Edit Mahasiswa - SI Akademik';

include __DIR__ . '/../layouts/main.php';

?>
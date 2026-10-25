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

        <div class="col-lg-8">

            <div class="card shadow-sm">

                <div class="card-header">

                    <h5 class="mb-0">
                        Tambah Mata Kuliah
                    </h5>

                </div>

                <div class="card-body">

                    <form
                        action="<?= htmlspecialchars(
                            $basePath . '/matakuliah/create'
                        ) ?>"
                        method="POST"
                    >

                        <div class="mb-3">

                            <label class="form-label">
                                Kode Mata Kuliah
                            </label>

                            <input
                                type="text"
                                name="kode"
                                class="form-control"
                                maxlength="20"
                                required
                            >

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Nama Mata Kuliah
                            </label>

                            <input
                                type="text"
                                name="nama"
                                class="form-control"
                                maxlength="100"
                                required
                            >

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                SKS
                            </label>

                            <input
                                type="number"
                                name="sks"
                                class="form-control"
                                min="1"
                                max="6"
                                required
                            >

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Program Studi
                            </label>

                            <select
                                name="prodi_id"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    -- Pilih Prodi --
                                </option>

                                <?php foreach (
                                    $prodi as $p
                                ): ?>

                                    <option
                                        value="<?= $p['id'] ?>"
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

                        <div class="d-flex justify-content-end gap-2">

                            <a
                                href="<?= htmlspecialchars(
                                    $basePath . '/matakuliah'
                                ) ?>"
                                class="btn btn-secondary"
                            >
                                Kembali
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
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

$title = 'Tambah Mata Kuliah - SI Akademik';

include __DIR__ . '/../layouts/main.php';

?>
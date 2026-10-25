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
                        Edit Program Studi
                    </h5>

                </div>

                <div class="card-body">

                    <form
                        action="<?= htmlspecialchars(
                            $basePath
                            . '/prodi/'
                            . $prodi['id']
                            . '/edit'
                        ) ?>"
                        method="POST"
                    >

                        <div class="mb-3">

                            <label class="form-label">
                                Kode Prodi
                            </label>

                            <input
                                type="text"
                                name="kode"
                                class="form-control"
                                maxlength="10"
                                value="<?= htmlspecialchars(
                                    $prodi['kode']
                                ) ?>"
                                required
                            >

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Nama Program Studi
                            </label>

                            <input
                                type="text"
                                name="nama"
                                class="form-control"
                                maxlength="100"
                                value="<?= htmlspecialchars(
                                    $prodi['nama']
                                ) ?>"
                                required
                            >

                        </div>

                        <div class="d-flex justify-content-end gap-2">

                            <a
                                href="<?= htmlspecialchars(
                                    $basePath . '/prodi'
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

$title = 'Edit Prodi - SI Akademik';

include __DIR__ . '/../layouts/main.php';

?>
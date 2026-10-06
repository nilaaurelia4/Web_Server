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

    <?php if (!empty($flash)): ?>
        <div class="alert alert-<?= htmlspecialchars(
            $flash['type'],
            ENT_QUOTES,
            'UTF-8'
        ) ?> alert-dismissible fade show" role="alert">

            <?= htmlspecialchars(
                $flash['message'],
                ENT_QUOTES,
                'UTF-8'
            ) ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>

        </div>
    <?php endif; ?>

    <div class="row mb-4">

        <div class="col-12">

            <div class="card">

                <div class="card-header">
                    Dashboard
                </div>

                <div class="card-body">

                    <h4 class="mb-3">
                        Selamat datang,
                        <?= htmlspecialchars(
                            $user['nama'] ?? 'User',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>!
                    </h4>

                    <p class="mb-0">
                        Selamat datang di Sistem Informasi Akademik & Manajemen Data Mahasiswa.
                    </p>

                </div>

            </div>

        </div>

    </div>


    <div class="row g-4">

        <div class="col-md-6">

            <a
                href="<?= htmlspecialchars(
                    $basePath . '/mahasiswa'
                ) ?>"
                class="text-decoration-none"
            >

                <div class="card menu-card h-100">

                    <div class="card-body text-center p-4">

                        <h5 class="card-title">
                            Data Mahasiswa
                        </h5>

                        <p class="mb-0">
                            Kelola data mahasiswa
                        </p>

                    </div>

                </div>

            </a>

        </div>


        <div class="col-md-6">

            <a
                href="<?= htmlspecialchars(
                    $basePath . '/mahasiswa/create'
                ) ?>"
                class="text-decoration-none"
            >

                <div class="card menu-card h-100">

                    <div class="card-body text-center p-4">

                        <h5 class="card-title">
                            Tambah Mahasiswa
                        </h5>

                        <p class="mb-0">
                            Tambahkan data mahasiswa baru
                        </p>

                    </div>

                </div>

            </a>

        </div>

    </div>

</div>

<?php

$content = ob_get_clean();

$title = 'Dashboard - SI Akademik';

include __DIR__ . '/../layouts/main.php';

?>

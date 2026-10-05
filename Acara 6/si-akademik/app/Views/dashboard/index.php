<?php

ob_start();

$basePath = dirname($_SERVER['SCRIPT_NAME'] ?? '/');
$basePath = $basePath === '.' ? '' : $basePath;

?>

<div class="container-fluid content">

    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">

            <span>
                Dashboard
            </span>

        </div>

    </div>


   <div class="row g-3 justify-content-center">

    <div class="col-md-5 col-lg-4">

        <a href="<?= htmlspecialchars($basePath . '/mahasiswa') ?>"
           class="card menu-card text-decoration-none text-navy h-100">

            <div class="card-body text-center py-4">

                <h5 class="card-title mb-2">
                    Data Mahasiswa
                </h5>

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
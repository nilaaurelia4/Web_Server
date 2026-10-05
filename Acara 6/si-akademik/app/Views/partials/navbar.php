<?php
$basePath = rtrim(
    str_replace(
        '\\',
        '/',
        dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php')
    ),
    '/'
);
?>

<nav class="navbar navbar-expand-lg">

    <div class="container-fluid px-4">

        <a class="navbar-brand" href="#">
            SI Akademik
        </a>


        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav">

            <span class="navbar-toggler-icon"></span>

        </button>


        <div class="collapse navbar-collapse"
             id="navbarNav">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">

                    <a class="nav-link" href="#">
                        Dashboard
                    </a>

                </li>


                <li class="nav-item">

                    <a class="nav-link active" href="#">
                        Mahasiswa
                    </a>

                </li>


                <li class="nav-item">

                    <a class="nav-link" href="#">
                        Dosen
                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link" href="<?= htmlspecialchars($basePath . '/logout') ?>">
                        Logout
                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>
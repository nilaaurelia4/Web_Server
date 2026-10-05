<nav class="navbar navbar-expand-lg">

    <div class="container-fluid px-4">

        <a
            class="navbar-brand"
            href="<?= htmlspecialchars(
                $basePath . '/dashboard'
            ) ?>"
        >
            SI Akademik
        </a>


        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >

            <span class="navbar-toggler-icon"></span>

        </button>


        <div
            class="collapse navbar-collapse"
            id="navbarNav"
        >

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="<?= htmlspecialchars(
                            $basePath . '/dashboard'
                        ) ?>"
                    >
                        Dashboard
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="<?= htmlspecialchars(
                            $basePath . '/mahasiswa'
                        ) ?>"
                    >
                        Mahasiswa
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="<?= htmlspecialchars(
                            $basePath . '/logout'
                        ) ?>"
                    >
                        Logout
                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>

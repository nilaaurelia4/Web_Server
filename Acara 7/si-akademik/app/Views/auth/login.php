<?php

$title = 'Login - SI Akademik';

$basePath = rtrim(
    str_replace(
        '\\',
        '/',
        dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php')
    ),
    '/'
);

include __DIR__ . '/../partials/header.php';
?>

<div class="container">
    <div class="row justify-content-center mt-5">

        <div class="col-md-5">

            <div class="card">

                <div class="card-header text-center">
                    Login SI Akademik
                </div>

                <div class="card-body">

                    <?php if (!empty($flash)): ?>

                        <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show">

                            <?= htmlspecialchars($flash['message']) ?>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="alert">
                            </button>

                        </div>

                    <?php endif; ?>

                    <form
                        action="<?= htmlspecialchars($basePath . '/login') ?>"
                        method="POST">

                        <div class="mb-3">

                            <label
                                for="username"
                                class="form-label">
                                Username
                            </label>

                            <input
                                type="text"
                                name="username"
                                id="username"
                                class="form-control"
                                required>

                        </div>

                        <div class="mb-3">

                            <label
                                for="password"
                                class="form-label">
                                Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control"
                                required>

                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary w-100">
                            Login
                        </button>

                    </form>

                    <div class="text-center mt-3">
                        <small class="text-muted">
                            Username: admin | Password: 12345
                        </small>
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

<?php
include __DIR__ . '/../partials/footer.php';
?>
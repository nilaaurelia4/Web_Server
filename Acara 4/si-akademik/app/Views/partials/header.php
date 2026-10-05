<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        <?= $title ?? 'SI Akademik' ?>
    </title>

    <!-- Bootstrap 5 CDN -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background-color: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background-color: #0b1f3a;
        }

        .navbar-brand {
            font-weight: bold;
            color: white !important;
        }

        .nav-link {
            color: white !important;
        }

        .nav-link:hover {
            color: #d1d5db !important;
        }

        .content {
            padding: 35px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            background-color: #0b1f3a;
            color: white;
            font-weight: bold;
            padding: 15px 20px;
            border-radius: 10px 10px 0 0 !important;
        }

        .table th {
            background-color: #0b1f3a;
            color: white;
            vertical-align: middle;
        }

        .table td {
            vertical-align: middle;
        }

        .btn-primary {
            background-color: #0b1f3a;
            border-color: #0b1f3a;
        }

        .btn-primary:hover {
            background-color: #08172b;
            border-color: #08172b;
        }

        .btn {
            border-radius: 6px;
        }

        .footer {
            margin-top: 40px;
            padding: 20px;
            text-align: center;
            background-color: #0b1f3a;
            color: white;
        }

        @media (max-width: 768px) {

            .content {
                padding: 20px;
            }

        }

    </style>

</head>

<body>

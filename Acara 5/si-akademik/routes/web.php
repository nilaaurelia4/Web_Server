<?php

$routes = [
    'GET' => [
        '/' => ['HomeController', 'index'],
        '/mahasiswa' => ['MahasiswaController', 'index'],
        '/mahasiswa/create' => ['MahasiswaController', 'create'],
        '/mahasiswa/{id}' => ['MahasiswaController', 'show'],
        '/mahasiswa/{id}/edit' => ['MahasiswaController', 'edit'],
    ],

    'POST' => [
        '/mahasiswa/create' => ['MahasiswaController', 'store'],
        '/mahasiswa/{id}/edit' => ['MahasiswaController', 'update'],
        '/mahasiswa/{id}/delete' => ['MahasiswaController', 'destroy'],
    ],
];
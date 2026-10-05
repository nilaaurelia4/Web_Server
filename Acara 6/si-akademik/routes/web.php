<?php

$routes = [

    'GET' => [

        '/' => [
            'HomeController',
            'index'
        ],

        '/login' => [
            'AuthController',
            'loginForm'
        ],

        '/logout' => [
            'AuthController',
            'logout'
        ],

        '/dashboard' => [
            'HomeController',
            'dashboard',
            ['auth']
        ],

        '/mahasiswa' => [
            'MahasiswaController',
            'index',
            ['auth']
        ],

        '/mahasiswa/create' => [
            'MahasiswaController',
            'create',
            ['auth']
        ],

        '/mahasiswa/{id}' => [
            'MahasiswaController',
            'show',
            ['auth']
        ],

        '/mahasiswa/{id}/edit' => [
            'MahasiswaController',
            'edit',
            ['auth']
        ],
    ],

    'POST' => [

        '/login' => [
            'AuthController',
            'login'
        ],

        '/mahasiswa/create' => [
            'MahasiswaController',
            'store',
            ['auth']
        ],

        '/mahasiswa/{id}/edit' => [
            'MahasiswaController',
            'update',
            ['auth']
        ],

        '/mahasiswa/{id}/delete' => [
            'MahasiswaController',
            'destroy',
            ['auth']
        ],
    ],
];
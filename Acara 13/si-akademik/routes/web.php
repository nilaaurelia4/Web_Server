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

        /*
        |--------------------------------------------------------------------------
        | MAHASISWA
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | PRODI
        |--------------------------------------------------------------------------
        */

        '/prodi' => [
            'ProdiController',
            'index',
            ['auth']
        ],

        '/prodi/create' => [
            'ProdiController',
            'create',
            ['auth']
        ],

        '/prodi/{id}/edit' => [
            'ProdiController',
            'edit',
            ['auth']
        ],

        /*
        |--------------------------------------------------------------------------
        | MATA KULIAH
        |--------------------------------------------------------------------------
        */

        '/matakuliah' => [
            'MataKuliahController',
            'index',
            ['auth']
        ],

        '/matakuliah/create' => [
            'MataKuliahController',
            'create',
            ['auth']
        ],

        '/matakuliah/{id}/edit' => [
            'MataKuliahController',
            'edit',
            ['auth']
        ],
    ],

    'POST' => [

        '/login' => [
            'AuthController',
            'login'
        ],

        /*
        |--------------------------------------------------------------------------
        | MAHASISWA
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | PRODI
        |--------------------------------------------------------------------------
        */

        '/prodi/create' => [
            'ProdiController',
            'store',
            ['auth']
        ],

        '/prodi/{id}/edit' => [
            'ProdiController',
            'update',
            ['auth']
        ],

        '/prodi/{id}/delete' => [
            'ProdiController',
            'destroy',
            ['auth']
        ],

        /*
        |--------------------------------------------------------------------------
        | MATA KULIAH
        |--------------------------------------------------------------------------
        */

        '/matakuliah/create' => [
            'MataKuliahController',
            'store',
            ['auth']
        ],

        '/matakuliah/{id}/edit' => [
            'MataKuliahController',
            'update',
            ['auth']
        ],

        '/matakuliah/{id}/delete' => [
            'MataKuliahController',
            'destroy',
            ['auth']
        ],
    ],
];
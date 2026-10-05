<?php

require_once __DIR__ . '/../app/Models/Mahasiswa.php';


// Membuat object mahasiswa
$mahasiswa = [

    new Mahasiswa(
        '25001',
        'Andi Pratama',
        'Teknik Informatika'
    ),

    new Mahasiswa(
        '25002',
        'Budi Santoso',
        'Sistem Informasi'
    ),

    new Mahasiswa(
        '25003',
        'Citra Lestari',
        'Teknik Komputer'
    )

];


// Mengirim data ke View
include __DIR__ . '/../app/Views/mahasiswa/index.php';

?>
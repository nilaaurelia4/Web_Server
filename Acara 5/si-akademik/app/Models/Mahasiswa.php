<?php

class Mahasiswa
{
    private $nim;
    private $nama;
    private $prodi;

    public function __construct($nim, $nama, $prodi)
    {
        $this->nim = $nim;
        $this->nama = $nama;
        $this->prodi = $prodi;
    }

    public function getNim()
    {
        return $this->nim;
    }

    public function getNama()
    {
        return $this->nama;
    }

    public function getProdi()
    {
        return $this->prodi;
    }

    public function getAngkatan()
    {
        $tahun = substr($this->nim, 0, 2);

        return "20" . $tahun;
    }

    public function setNim($nim)
    {
        $this->nim = trim($nim);
    }

    public function setNama($nama)
    {
        $this->nama = trim($nama);
    }

    public function setProdi($prodi)
    {
        $this->prodi = trim($prodi);
    }

    public static function defaultData(): array
    {
        return [
            new self('230001', 'Andi Pratama', 'Teknik Informatika'),
            new self('240002', 'Budi Santoso', 'Sistem Informasi'),
            new self('250003', 'Citra Lestari', 'Teknik Komputer'),
        ];
    }
}
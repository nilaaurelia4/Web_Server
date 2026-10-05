<?php

namespace App\Models;

use InvalidArgumentException;

class Mahasiswa
{
    private ?int $id = null;
    private string $nim = '';
    private string $nama = '';
    private string $email = '';
    private int $prodiId = 0;
    private int $angkatan = 0;
    private string $status = 'aktif';

    public function __construct(
        ?int $id = null,
        string $nim = '',
        string $nama = '',
        string $email = '',
        int $prodiId = 0,
        int $angkatan = 0,
        string $status = 'aktif'
    ) {
        if ($id !== null) {
            $this->setId($id);
        }

        if ($nim !== '') {
            $this->setNim($nim);
        }

        if ($nama !== '') {
            $this->setNama($nama);
        }

        if ($email !== '') {
            $this->setEmail($email);
        }

        if ($prodiId > 0) {
            $this->setProdiId($prodiId);
        }

        if ($angkatan > 0) {
            $this->setAngkatan($angkatan);
        }

        $this->setStatus($status);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        if ($id !== null && $id < 1) {
            throw new InvalidArgumentException('ID tidak valid.');
        }

        $this->id = $id;
    }

    public function getNim(): string
    {
        return $this->nim;
    }

    public function setNim(string $nim): void
    {
        $nim = trim($nim);

        if ($nim === '') {
            throw new InvalidArgumentException(
                'NIM tidak boleh kosong.'
            );
        }

        if (!ctype_digit($nim)) {
            throw new InvalidArgumentException(
                'NIM harus berupa angka.'
            );
        }

        $this->nim = $nim;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function setNama(string $nama): void
    {
        $nama = trim($nama);

        if ($nama === '') {
            throw new InvalidArgumentException(
                'Nama mahasiswa tidak boleh kosong.'
            );
        }

        $this->nama = $nama;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $email = trim($email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(
                'Format email tidak valid.'
            );
        }

        $this->email = $email;
    }

    public function getProdiId(): int
    {
        return $this->prodiId;
    }

    public function setProdiId(int $prodiId): void
    {
        if ($prodiId < 1) {
            throw new InvalidArgumentException(
                'Prodi harus dipilih.'
            );
        }

        $this->prodiId = $prodiId;
    }

    public function getAngkatan(): int
    {
        return $this->angkatan;
    }

    public function setAngkatan(int $angkatan): void
    {
        if ($angkatan < 2000 || $angkatan > 2100) {
            throw new InvalidArgumentException(
                'Tahun angkatan tidak valid.'
            );
        }

        $this->angkatan = $angkatan;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $status = strtolower(trim($status));

        if (!in_array(
            $status,
            ['aktif', 'cuti', 'lulus'],
            true
        )) {
            throw new InvalidArgumentException(
                'Status mahasiswa tidak valid.'
            );
        }

        $this->status = $status;
    }
}
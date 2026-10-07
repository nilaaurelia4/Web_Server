<?php

namespace App\Services;

use App\Models\Mahasiswa;
use App\Repositories\MahasiswaRepository;
use App\Repositories\ProdiRepository;
use InvalidArgumentException;
use PDOException;

class MahasiswaService
{
    private MahasiswaRepository $repo;
    private ProdiRepository $prodiRepo;

    public function __construct(
        MahasiswaRepository $repo,
        ProdiRepository $prodiRepo
    ) {
        $this->repo = $repo;
        $this->prodiRepo = $prodiRepo;
    }

    public function create(array $data): array
    {
        try {

            $mahasiswa = $this->validate($data);

            if ($this->repo->existsByNim($mahasiswa->getNim())) {
                throw new InvalidArgumentException(
                    'NIM sudah digunakan.'
                );
            }

            if (
                !$this->prodiRepo->exists(
                    $mahasiswa->getProdiId()
                )
            ) {
                throw new InvalidArgumentException(
                    'Program studi tidak ditemukan.'
                );
            }

            $this->repo->create($mahasiswa);

            return [
                'success' => true,
                'message' => 'Data mahasiswa berhasil ditambahkan.'
            ];

        } catch (InvalidArgumentException $e) {

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];

        } catch (PDOException $e) {

            return [
                'success' => false,
                'message' => 'Data mahasiswa gagal ditambahkan.'
            ];
        }
    }


    public function update(int $id, array $data): array
    {
        try {

            $mahasiswa = $this->validate($data);

            if (
                $this->repo->existsByNim(
                    $mahasiswa->getNim(),
                    $id
                )
            ) {
                throw new InvalidArgumentException(
                    'NIM sudah digunakan oleh mahasiswa lain.'
                );
            }

            if (
                !$this->prodiRepo->exists(
                    $mahasiswa->getProdiId()
                )
            ) {
                throw new InvalidArgumentException(
                    'Program studi tidak ditemukan.'
                );
            }

            $this->repo->update(
                $id,
                $mahasiswa
            );

            return [
                'success' => true,
                'message' => 'Data mahasiswa berhasil diperbarui.'
            ];

        } catch (InvalidArgumentException $e) {

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];

        } catch (PDOException $e) {

            return [
                'success' => false,
                'message' => 'Data mahasiswa gagal diperbarui.'
            ];
        }
    }


    private function validate(array $data): Mahasiswa
    {
        $nim = trim($data['nim'] ?? '');
        $nama = trim($data['nama'] ?? '');
        $email = trim($data['email'] ?? '');
        $prodiId = (int) ($data['prodi_id'] ?? 0);
        $angkatan = (int) ($data['angkatan'] ?? 0);

        if ($nim === '') {
            throw new InvalidArgumentException(
                'NIM wajib diisi.'
            );
        }

        if (!ctype_digit($nim)) {
            throw new InvalidArgumentException(
                'NIM harus berupa angka.'
            );
        }

        if ($nama === '') {
            throw new InvalidArgumentException(
                'Nama wajib diisi.'
            );
        }

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(
                'Format email tidak valid.'
            );
        }

        if ($prodiId <= 0) {
            throw new InvalidArgumentException(
                'Program studi wajib dipilih.'
            );
        }

        if ($angkatan < 2000 || $angkatan > 2100) {
            throw new InvalidArgumentException(
                'Tahun angkatan tidak valid.'
            );
        }

        /*
         * Dua digit pertama NIM harus sesuai
         * dengan dua digit terakhir tahun angkatan.
         *
         * Contoh:
         * Angkatan 2024 → NIM harus diawali 24
         */
        $duaDigitAngkatan = substr((string) $angkatan, -2);
        $duaDigitNim = substr($nim, 0, 2);

        if ($duaDigitNim !== $duaDigitAngkatan) {
            throw new InvalidArgumentException(
                'Dua digit pertama NIM harus sesuai dengan tahun angkatan.'
            );
        }

        $mahasiswa = new Mahasiswa();

        $mahasiswa->setNim($nim);
        $mahasiswa->setNama($nama);
        $mahasiswa->setEmail($email);
        $mahasiswa->setProdiId($prodiId);
        $mahasiswa->setAngkatan($angkatan);

        return $mahasiswa;
    }
}
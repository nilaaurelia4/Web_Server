<?php

namespace App\Services;

use App\Models\Mahasiswa;
use App\Repositories\MahasiswaRepository;
use App\Repositories\ProdiRepository;
use InvalidArgumentException;
use PDOException;
use Throwable;

class MahasiswaService
{
    private MahasiswaRepository $repo;
    private ProdiRepository $prodiRepo;
    private LoggerService $logger;


    public function __construct(
        MahasiswaRepository $repo,
        ProdiRepository $prodiRepo,
        LoggerService $logger
    ) {
        $this->repo = $repo;
        $this->prodiRepo = $prodiRepo;
        $this->logger = $logger;
    }


    /**
     * Menambahkan mahasiswa.
     */
    public function create(
        array $data
    ): array {

        try {

            $mahasiswa =
                $this->validate($data);


            /*
             * Cek NIM duplikat.
             */
            if (
                $this->repo->existsByNim(
                    $mahasiswa->getNim()
                )
            ) {
                throw new InvalidArgumentException(
                    'NIM sudah terdaftar.'
                );
            }


            /*
             * Pastikan prodi tersedia.
             */
            if (
                !$this->prodiRepo->exists(
                    $mahasiswa->getProdiId()
                )
            ) {
                throw new InvalidArgumentException(
                    'Program studi tidak ditemukan.'
                );
            }


            $this->repo->create(
                $mahasiswa
            );


            return [
                'success' => true,
                'message' =>
                    'Data mahasiswa berhasil ditambahkan.'
            ];

        } catch (InvalidArgumentException $e) {

            /*
             * Error validasi boleh diberikan
             * kepada pengguna.
             */
            return [
                'success' => false,
                'message' =>
                    $e->getMessage()
            ];

        } catch (PDOException $e) {

            /*
             * Detail error database hanya
             * dimasukkan ke log.
             */
            $this->logger->error($e);


            return [
                'success' => false,
                'message' =>
                    'Data mahasiswa gagal disimpan.'
            ];

        } catch (Throwable $e) {

            $this->logger->error($e);


            return [
                'success' => false,
                'message' =>
                    'Terjadi kesalahan pada aplikasi.'
            ];
        }
    }


    /**
     * Mengubah mahasiswa.
     */
    public function update(
        int $id,
        array $data
    ): array {

        try {

            if ($this->repo->find($id) === null) {
                throw new InvalidArgumentException(
                    'Data mahasiswa tidak ditemukan.'
                );
            }


            $mahasiswa =
                $this->validate($data);


            /*
             * Cek NIM duplikat tetapi abaikan
             * ID mahasiswa yang sedang diedit.
             */
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
                'message' =>
                    'Data mahasiswa berhasil diubah.'
            ];

        } catch (InvalidArgumentException $e) {

            return [
                'success' => false,
                'message' =>
                    $e->getMessage()
            ];

        } catch (PDOException $e) {

            $this->logger->error($e);


            return [
                'success' => false,
                'message' =>
                    'Data mahasiswa gagal diubah.'
            ];

        } catch (Throwable $e) {

            $this->logger->error($e);


            return [
                'success' => false,
                'message' =>
                    'Terjadi kesalahan pada aplikasi.'
            ];
        }
    }


    /**
     * Menghapus mahasiswa.
     */
    public function delete(
        int $id
    ): array {

        try {

            if ($id <= 0) {
                throw new InvalidArgumentException(
                    'ID mahasiswa tidak valid.'
                );
            }


            if ($this->repo->find($id) === null) {
                throw new InvalidArgumentException(
                    'Data mahasiswa tidak ditemukan.'
                );
            }


            $this->repo->delete($id);


            return [
                'success' => true,
                'message' =>
                    'Data mahasiswa berhasil dihapus.'
            ];

        } catch (InvalidArgumentException $e) {

            return [
                'success' => false,
                'message' =>
                    $e->getMessage()
            ];

        } catch (PDOException $e) {

            $this->logger->error($e);


            return [
                'success' => false,
                'message' =>
                    'Data mahasiswa gagal dihapus.'
            ];

        } catch (Throwable $e) {

            $this->logger->error($e);


            return [
                'success' => false,
                'message' =>
                    'Terjadi kesalahan pada aplikasi.'
            ];
        }
    }


    /**
     * Validasi input mahasiswa.
     */
    private function validate(
        array $data
    ): Mahasiswa {

        $nim =
            trim(
                $data['nim'] ?? ''
            );

        $nama =
            trim(
                $data['nama'] ?? ''
            );

        $email =
            trim(
                $data['email'] ?? ''
            );

        $prodiId =
            (int) (
                $data['prodi_id'] ?? 0
            );

        $angkatan =
            (int) (
                $data['angkatan'] ?? 0
            );

        $status =
            strtolower(
                trim(
                    $data['status'] ?? 'aktif'
                )
            );


        /*
         * Validasi NIM.
         */
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


        /*
         * Validasi nama.
         */
        if ($nama === '') {
            throw new InvalidArgumentException(
                'Nama wajib diisi.'
            );
        }


        /*
         * Validasi email.
         */
        if ($email === '') {
            throw new InvalidArgumentException(
                'Email wajib diisi.'
            );
        }


        if (
            !filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            )
        ) {
            throw new InvalidArgumentException(
                'Format email tidak valid.'
            );
        }


        /*
         * Validasi program studi.
         */
        if ($prodiId <= 0) {
            throw new InvalidArgumentException(
                'Program studi wajib dipilih.'
            );
        }


        /*
         * Validasi angkatan.
         */
        if (
            $angkatan < 2000 ||
            $angkatan > 2100
        ) {
            throw new InvalidArgumentException(
                'Tahun angkatan tidak valid.'
            );
        }


        /*
         * Dua digit pertama NIM
         * harus sama dengan dua digit
         * terakhir tahun angkatan.
         *
         * 2025 -> 25xxxx
         */
        $duaDigitAngkatan =
            substr(
                (string) $angkatan,
                -2
            );

        $duaDigitNim =
            substr(
                $nim,
                0,
                2
            );


        if (
            $duaDigitNim !==
            $duaDigitAngkatan
        ) {
            throw new InvalidArgumentException(
                'Dua digit pertama NIM harus sesuai dengan tahun angkatan.'
            );
        }


        /*
         * Validasi status.
         */
        if (
            !in_array(
                $status,
                [
                    'aktif',
                    'cuti',
                    'lulus'
                ],
                true
            )
        ) {
            throw new InvalidArgumentException(
                'Status mahasiswa tidak valid.'
            );
        }


        /*
         * Membentuk object mahasiswa
         * setelah seluruh data valid.
         */
        $mahasiswa =
            new Mahasiswa();


        $mahasiswa->setNim(
            $nim
        );

        $mahasiswa->setNama(
            $nama
        );

        $mahasiswa->setEmail(
            $email
        );

        $mahasiswa->setProdiId(
            $prodiId
        );

        $mahasiswa->setAngkatan(
            $angkatan
        );

        $mahasiswa->setStatus(
            $status
        );


        return $mahasiswa;
    }
}
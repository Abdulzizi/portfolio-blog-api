<?php

namespace App\Helpers\User;

use App\Helpers\Venturo;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;
use Throwable;

/**
 * Helper untuk manajemen user
 * Mengambil data, menambah, mengubah, & menghapus ke tabel users
 *
 * @author Wahyu Agung <wahyuagung26@gmail.com>
 */
class UserHelper extends Venturo
{
    const USER_PHOTO_DIRECTORY = 'foto-user';

    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel;
    }

    /**
     * method untuk menginput data baru ke tabel users
     *
     * @author Wahyu Agung <wahyuagung26@email.com>
     *
     * @param  array  $payload
     *                          $payload['name'] = string
     *                          $payload['email] = string
     *                          $payload['password] = string
     */
    public function create(array $payload): array
    {
        try {
            $payload['password'] = Hash::make($payload['password']);

            $payload = $this->uploadGetPayload($payload);
            $user = $this->userModel->store($payload);

            return [
                'status' => true,
                'data' => $user,
            ];
        } catch (Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage(),
            ];
        }
    }

    /**
     * Menghapus data user dengan sistem "Soft Delete"
     * yaitu mengisi kolom deleted_at agar data tsb tidak
     * keselect waktu menggunakan Query
     *
     * @param  int  $id  id dari tabel users
     */
    public function delete(string $id): bool
    {
        try {
            $this->userModel->drop($id);

            return true;
        } catch (Throwable $th) {
            return false;
        }
    }

    /**
     * Mengambil data user dari tabel users
     *
     * @author Wahyu Agung <wahyuagung26@gmail.com>
     *
     * @param  array  $filter
     *                         $filter['name'] = string
     *                         $filter['email'] = string
     * @param  int  $itemPerPage  jumlah data yang ditampilkan, kosongi jika ingin menampilkan semua data
     * @param  string  $sort  nama kolom untuk melakukan sorting mysql beserta tipenya DESC / ASC
     * @return array
     */
    public function getAll(array $filter, int $page = 1, int $itemPerPage = 0, string $sort = '')
    {
        $users = $this->userModel->getAll($filter, $page, $itemPerPage, $sort);

        return [
            'status' => true,
            'data' => $users,
        ];
    }

    /**
     * Mengambil 1 data user dari tabel users
     *
     * @param  int  $id  id dari tabel users
     */
    public function getById(string $id): array
    {
        $user = $this->userModel->getById($id);
        if (empty($user)) {
            return [
                'status' => false,
                'data' => null,
            ];
        }

        return [
            'status' => true,
            'data' => $user,
        ];
    }

    /**
     * method untuk mengubah user pada tabel users
     *
     * @author Wahyu Agung <wahyuagung26@email.com>
     *
     * @param  array  $payload
     *                          $payload['name'] = string
     *                          $payload['email] = string
     *                          $payload['password] = string
     */
    public function update(array $payload, string $id): array
    {
        try {
            if (isset($payload['password']) && ! empty($payload['password'])) {
                $payload['password'] = Hash::make($payload['password']) ?: '';
            } else {
                unset($payload['password']);
            }

            $payload = $this->uploadGetPayload($payload);
            $this->userModel->edit($payload, $id);

            $user = $this->getById($id);

            return [
                'status' => true,
                'data' => $user['data'],
            ];
        } catch (Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage(),
            ];
        }
    }

    /**
     * Upload file and remove payload when photo is not exist
     *
     * @author Wahyu Agung <wahyuagung26@email.com>
     *
     * @return array
     */
    private function uploadGetPayload(array $payload)
    {
        /**
         * Jika dalam payload terdapat base64 foto, maka Upload foto ke folder public/uploads/foto-user
         */
        if (! empty($payload['photo'])) {
            $fileName = $this->generateFileName($payload['photo'], 'USER_' . date('Ymdhis'));
            $photo = $payload['photo']->storeAs(self::USER_PHOTO_DIRECTORY, $fileName, 'public');
            $payload['photo'] = $photo;
        } else {
            unset($payload['photo']);
        }

        return $payload;
    }
}

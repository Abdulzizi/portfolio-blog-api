<?php

namespace App\Helpers\Post;

use App\Helpers\Venturo;
use App\Models\PostModel;
use Throwable;

/**
 * Helper untuk manajemen post
 * Mengambil data, menambah, mengubah, & menghapus ke tabel posts
 *
 * @author Your Name
 */
class PostHelper extends Venturo
{
    const POST_IMAGE_DIRECTORY = 'post-images';

    private $postModel;

    public function __construct()
    {
        $this->postModel = new PostModel();
    }

    public function create(array $payload): array
    {
        try {
            $payload = $this->uploadGetPayload($payload);
            $post = $this->postModel->store($payload);

            return [
                'status' => true,
                'data' => $post,
            ];
        } catch (Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage(),
            ];
        }
    }

    public function delete(string $id): bool
    {
        try {
            $this->postModel->drop($id);
            return true;
        } catch (Throwable $th) {
            return false;
        }
    }

    public function getAll(array $filter, int $page = 1, int $itemPerPage = 0, string $sort = ''): array
    {
        $posts = $this->postModel->getAll($filter, $page, $itemPerPage, $sort);

        return [
            'status' => true,
            'data' => $posts,
        ];
    }

    public function getById(string $id): array
    {
        $post = $this->postModel->getById($id);
        if (empty($post)) {
            return [
                'status' => false,
                'data' => null,
            ];
        }

        return [
            'status' => true,
            'data' => $post,
        ];
    }

    public function update(array $payload, string $id): array
    {
        try {
            $payload = $this->uploadGetPayload($payload);
            $this->postModel->edit($payload, $id);

            $post = $this->getById($id);

            return [
                'status' => true,
                'data' => $post['data'],
            ];
        } catch (Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage(),
            ];
        }
    }

    private function uploadGetPayload(array $payload)
    {
        if (!empty($payload['image'])) {
            $fileName = $this->generateFileName($payload['image'], 'POST_' . date('Ymdhis'));
            $imagePath = $payload['image']->storeAs(self::POST_IMAGE_DIRECTORY, $fileName, 'public');
            $payload['image'] = $imagePath;
        } else {
            unset($payload['image']);
        }

        return $payload;
    }
}

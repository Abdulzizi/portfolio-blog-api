<?php

namespace App\Helpers\Tag;

use App\Helpers\Venturo;
use App\Models\TagModel;
use Throwable;

class TagHelper extends Venturo
{

    private $tagModel;

    public function __construct()
    {
        $this->tagModel = new TagModel;
    }

    public function create(array $payload): array
    {
        try {
            $tag = $this->tagModel->store($payload);

            return [
                'status' => true,
                'data' => $tag,
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
            $this->tagModel->drop($id);
            return true;
        } catch (Throwable $th) {
            return false;
        }
    }

    public function getAll(array $filter, int $page = 1, int $itemPerPage = 0, string $sort = '')
    {
        return $this->tagModel->getAll($filter, $page, $itemPerPage, $sort);
    }

    public function getById(string $id): array
    {
        $tag = $this->tagModel->getById($id);

        if (empty($tag)) {
            return [
                'status' => false,
                'data' => null,
            ];
        }

        return [
            'status' => true,
            'data' => $tag,
        ];
    }

    public function update(array $payload, string $id): array
    {
        try {
            $tag = $this->tagModel->findOrFail($id);
            $tag->edit($payload, $id);

            return [
                'status' => true,
                'data' => $tag->fresh(),
            ];
        } catch (Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage(),
            ];
        }
    }
}

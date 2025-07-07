<?php

namespace App\Helpers\Project;

use App\Helpers\Venturo;
use App\Models\ProjectModel;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Throwable;

class ProjectHelper extends Venturo
{
    const IMAGE_DIRECTORY = 'project-images';

    private $projectModel;

    public function __construct()
    {
        $this->projectModel = new ProjectModel;
    }

    public function create(array $payload): array
    {
        try {
            $payload = $this->uploadAndGetPayload($payload);

            $project = $this->projectModel->store($payload);

            return [
                'status' => true,
                'data' => $project,
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
            $this->projectModel->drop($id);
            return true;
        } catch (Throwable $th) {
            return false;
        }
    }

    public function getAll(array $filter, int $page = 1, int $itemPerPage = 0, string $sort = '')
    {
        return $this->projectModel->getAll($filter, $page, $itemPerPage, $sort);
    }

    public function getById(string $id): array
    {
        $project = $this->projectModel->getById($id);

        if (empty($project)) {
            return [
                'status' => false,
                'data' => null,
            ];
        }

        return [
            'status' => true,
            'data' => $project,
        ];
    }

    public function getBySlug(string $slug): array
    {
        $project = $this->projectModel->where('slug', $slug)->first();
        if (empty($project)) {
            return [
                'status' => false,
                'data' => null
            ];
        }

        return [
            'status' => true,
            'data' => $project
        ];
    }


    public function update(array $payload, string $id): array
    {
        try {
            $payload = $this->uploadAndGetPayload($payload);

            $project = $this->projectModel->findOrFail($id);
            $project->edit($payload, $id);

            return [
                'status' => true,
                'data' => $project->fresh(),
            ];
        } catch (Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage(),
            ];
        }
    }

    private function uploadAndGetPayload(array $payload): array
    {
        if (!empty($payload['thumbnail'])) {
            $uploadedFile = Cloudinary::uploadApi()->upload($payload['thumbnail']->getRealPath(), [
                'folder' => self::IMAGE_DIRECTORY,
            ]);

            $payload['thumbnail'] = $uploadedFile['secure_url'];

            // TODO : Save public_id for deleting the image remotely (create public_id column in project table)
            // $payload['thumbnail_public_id'] = $uploadedFile['public_id'];
        } else {
            unset($payload['thumbnail']);
        }

        return $payload;
    }
}
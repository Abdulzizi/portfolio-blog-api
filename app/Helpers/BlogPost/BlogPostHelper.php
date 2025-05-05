<?php

namespace App\Helpers\BlogPost;

use App\Helpers\Venturo;
use App\Models\BlogPostModel;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Throwable;

class BlogPostHelper extends Venturo
{
    const BLOG_IMAGE_DIRECTORY = 'cover-image';

    private $blogModel;

    public function __construct()
    {
        $this->blogModel = new BlogPostModel;
    }

    public function create(array $payload): array
    {
        try {
            $payload = $this->uploadGetPayload($payload);

            // Create blog post
            $blog = $this->blogModel->store($payload);

            // Attach tags if provided
            if (!empty($payload['tags'])) {
                $blog->tags()->attach($payload['tags']);
            }

            return [
                'status' => true,
                'data' => $blog,
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
            $this->blogModel->drop($id);
            return true;
        } catch (Throwable $th) {
            return false;
        }
    }

    public function getAll(array $filter, int $page = 1, int $itemPerPage = 0, string $sort = '')
    {
        return $this->blogModel->getAll($filter, $page, $itemPerPage, $sort);
    }

    public function getById(string $id): array
    {
        $blog = $this->blogModel->getById($id);

        if (empty($blog)) {
            return [
                'status' => false,
                'data' => null,
            ];
        }

        return [
            'status' => true,
            'data' => $blog,
        ];
    }

    public function update(array $payload, string $id): array
    {
        try {
            $payload = $this->uploadGetPayload($payload);

            $blog = $this->blogModel->findOrFail($id);
            $blog->edit($payload, $id);

            // Handle tag replacement (completely replace the existing tags with the new ones)
            if (isset($payload['tags'])) {
                // This will replace the existing tags with the new ones (sync behavior)
                $blog->tags()->sync($payload['tags']);
            }

            // Handle adding new tags without removing the existing ones
            if (!empty($payload['tags_to_add'])) {
                // This will add only the new tags (attach behavior)
                $blog->tags()->attach($payload['tags_to_add']);
            }

            // Handle removing specific tags without affecting the others
            if (!empty($payload['tags_to_remove'])) {
                // This will remove only the specified tags (detach behavior)
                $blog->tags()->detach($payload['tags_to_remove']);
            }

            return [
                'status' => true,
                'data' => $blog->fresh('tags'),
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
        if (!empty($payload['cover_image'])) {
            $coverImage = $payload['cover_image'];

            // Store locally
            $fileName = 'COVER_' . time() . '.' . $coverImage->getClientOriginalExtension();
            $localPath = $coverImage->storeAs('blog-covers', $fileName, 'public');
            $payload['cover_image_local'] = $localPath;

            // Upload to Cloudinary
            $uploadedFileUrl = Cloudinary::upload($coverImage->getRealPath())->getSecurePath();
            $payload['cover_image'] = $uploadedFileUrl;
        } else {
            unset($payload['cover_image']);
            unset($payload['cover_image_local']);
        }

        return $payload;
    }
}

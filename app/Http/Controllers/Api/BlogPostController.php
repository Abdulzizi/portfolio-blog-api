<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\BlogPost\BlogPostHelper;
use App\Http\Requests\BlogPostRequest;
use App\Http\Resources\BlogPostResource;
use Illuminate\Http\Request;


class BlogPostController extends Controller
{
    private $blogPostHelper;

    public function __construct()
    {
        $this->blogPostHelper = new BlogPostHelper;
    }

    public function index(Request $request)
    {
        $filter = [
            'title' => $request->title ?? '',
            'is_published' => $request->is_published ?? null,
            'tags' => $request->tags ?? [],
        ];

        $posts = $this->blogPostHelper->getAll($filter, $request->page ?? 1, $request->per_page ?? 25, $request->sort ?? '');

        return response()->success([
            'list' => BlogPostResource::collection($posts['data']),
            'meta' => [
                'total' => $posts['total'],
            ],
        ]);
    }

    public function show(string $id)
    {
        $post = $this->blogPostHelper->getById($id);

        if (! $post['status']) {
            return response()->failed(['Sorry blog post not found'], 404);
        }

        return response()->success(new BlogPostResource($post['data']));
    }

    public function store(BlogPostRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['title', 'content', 'slug', 'is_published', 'cover_image', 'tags']);
        $payload['cover_image'] = $request->file('cover_image') ?? null;

        $post = $this->blogPostHelper->create($payload);

        if (! $post['status']) {
            return response()->failed($post['error']);
        }

        return response()->success(new BlogPostResource($post['data']), 'Blog post created successfully');
    }

    public function update(BlogPostRequest $request, string $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only([
            'title',
            'content',
            'slug',
            'is_published',
            'cover_image',
            'tags',
            'tags_to_add',
            'tags_to_remove',
        ]);

        $payload['cover_image'] = $request->file('cover_image') ?? null;

        $post = $this->blogPostHelper->update($payload, $id);

        if (! $post['status']) {
            return response()->failed($post['error']);
        }

        return response()->success(new BlogPostResource($post['data']), 'Blog post updated successfully');
    }

    public function destroy(string $id)
    {
        $deleted = $this->blogPostHelper->delete($id);

        if (! $deleted) {
            return response()->failed(['Sorry blog post not found'], 404);
        }

        return response()->success($deleted, 'Blog post deleted successfully');
    }
}

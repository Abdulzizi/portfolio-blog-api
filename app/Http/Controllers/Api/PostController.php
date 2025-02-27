<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Post\PostHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;

class PostController extends Controller
{
    private $postHelper;

    public function __construct()
    {
        $this->postHelper = new PostHelper;
    }

    public function destroy($id)
    {
        $post = $this->postHelper->delete($id);

        if (! $post) {
            return response()->failed(['Post not found']);
        }

        return response()->success($post, 'Post successfully deleted');
    }

    public function index(Request $request)
    {
        $filter = [
            'title' => $request->title ?? '',
            'category' => $request->category ?? '',
        ];
        $posts = $this->postHelper->getAll($filter, $request->page ?? 1, $request->per_page ?? 25, $request->sort ?? '');

        return response()->success([
            'list' => PostResource::collection($posts['data']),
            'meta' => [
                'total' => $posts['total'],
            ],
        ]);
    }

    public function show($id)
    {
        $post = $this->postHelper->getById($id);

        if (! ($post['status'])) {
            return response()->failed(['Post not found'], 404);
        }

        return response()->success(new PostResource($post['data']));
    }

    public function store(PostRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['title', 'content', 'category_id', 'tags', 'image']);
        $post = $this->postHelper->create($payload);

        if (! $post['status']) {
            return response()->failed($post['error']);
        }

        return response()->success(new PostResource($post['data']), 'Post successfully created');
    }

    public function update(PostRequest $request, $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['title', 'content', 'category_id', 'tags', 'image']);
        $post = $this->postHelper->update($payload, $id ?? 0);

        if (! $post['status']) {
            return response()->failed($post['error']);
        }

        return response()->success(new PostResource($post['data']), 'Post successfully updated');
    }
}

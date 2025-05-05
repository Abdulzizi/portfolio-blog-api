<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Tag\TagHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\TagRequest;
use App\Http\Resources\TagResource;
use Illuminate\Http\Request;

class TagController extends Controller
{
    private $tagHelper;

    public function __construct()
    {
        $this->tagHelper = new TagHelper;
    }

    public function index(Request $request)
    {
        $filter = [
            'name' => $request->name ?? '',
            'slug' => $request->slug ?? [],
        ];

        $posts = $this->tagHelper->getAll($filter, $request->page ?? 1, $request->per_page ?? 25, $request->sort ?? '');

        return response()->success([
            'list' => TagResource::collection($posts['data']),
            'meta' => [
                'total' => $posts['total'],
            ],
        ]);
    }

    public function show(string $id)
    {
        $post = $this->tagHelper->getById($id);

        if (! $post['status']) {
            return response()->failed(['Sorry tag not found'], 404);
        }

        return response()->success(new TagResource($post['data']));
    }

    public function store(TagRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only([
            'name',
            'description',
            'slug',
        ]);

        $post = $this->tagHelper->create($payload);

        if (! $post['status']) {
            return response()->failed($post['error']);
        }

        return response()->success(new TagResource($post['data']), 'Tag created successfully');
    }

    public function update(TagRequest $request, string $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only([
            'name',
            'description',
            'slug',
        ]);

        $post = $this->tagHelper->update($payload, $id);

        if (! $post['status']) {
            return response()->failed($post['error']);
        }

        return response()->success(new TagResource($post['data']), 'Tag updated successfully');
    }

    public function destroy(string $id)
    {
        $deleted = $this->tagHelper->delete($id);

        if (! $deleted) {
            return response()->failed(['Sorry tag not found'], 404);
        }

        return response()->success($deleted, 'Tag deleted successfully');
    }
}

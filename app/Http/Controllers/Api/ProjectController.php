<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Project\ProjectHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    private $projectHelper;

    public function __construct()
    {
        $this->projectHelper = new ProjectHelper;
    }

    public function index(Request $request)
    {
        $filter = [
            'title' => $request->title ?? '',
            'is_published' => $request->is_published ?? null,
        ];

        $projects = $this->projectHelper->getAll($filter, $request->page ?? 1, $request->per_page ?? 25, $request->sort ?? '');

        return response()->success([
            'list' => ProjectResource::collection($projects['data']),
            'meta' => [
                'total' => $projects['total'],
            ],
        ]);
    }

    public function show(string $id)
    {
        $project = $this->projectHelper->getById($id);

        if (! $project['status']) {
            return response()->failed(['Sorry project not found'], 404);
        }

        return response()->success(new ProjectResource($project['data']));
    }

    public function showBySlug(string $slug)
    {
        $project = $this->projectHelper->getBySlug($slug);

        if (!($project['status'])) {
            return response()->failed(['Data project not found'], 404);
        }

        return response()->success(new ProjectResource($project['data']));
    }

    public function store(ProjectRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only([
            'title',
            'description',
            'link',
            'tech_stack',
            'images',
            'thumbnail',
            'start_date',
            'end_date',
            'is_published',
        ]);

        $payload['thumbnail'] = $request->file('thumbnail') ?? null;

        if (isset($payload['tech_stack']) && is_array($payload['tech_stack'])) {
            $payload['tech_stack'] = json_encode($payload['tech_stack']);
        }

        // Ensure images is passed as file[] if needed
        if ($request->hasFile('images')) {
            $payload['images'] = $request->file('images');
        }

        $project = $this->projectHelper->create($payload);

        if (! $project['status']) {
            return response()->failed($project['error']);
        }

        return response()->success(new ProjectResource($project['data']), 'Project created successfully');
    }

    public function update(ProjectRequest $request, string $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only([
            'title',
            'description',
            'link',
            'tech_stack',
            'images',
            'thumbnail',
            'start_date',
            'end_date',
            'is_published',
        ]);

        $payload['thumbnail'] = $request->file('thumbnail') ?? $request->input('thumbnail');

        $project = $this->projectHelper->update($payload, $id);

        if (! $project['status']) {
            return response()->failed($project['error']);
        }

        return response()->success(new ProjectResource($project['data']), 'Project updated successfully');
    }

    public function destroy(string $id)
    {
        $deleted = $this->projectHelper->delete($id);

        if (! $deleted) {
            return response()->failed(['Sorry project not found'], 404);
        }

        return response()->success($deleted, 'Project deleted successfully');
    }
}
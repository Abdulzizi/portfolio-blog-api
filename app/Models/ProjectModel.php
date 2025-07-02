<?php

namespace App\Models;

use App\Helpers\SlugHelper;
use App\Http\Traits\Uuid;
use App\Repository\CrudInterface;
use Illuminate\Database\Eloquent\Model;

class ProjectModel extends Model implements CrudInterface
{
    use Uuid;

    protected $fillable = [
        'title',
        'description',
        'link',
        'tech_stack',
        'images',
        'start_date',
        'end_date',
        'thumbnail',
        'slug',
        'is_published'
    ];

    protected $table = 'projects';

    public function drop(string $id)
    {
        return $this->find($id)->delete();
    }

    public function edit(array $payload, string $id)
    {
        if (!empty($payload['title'])) {
            $payload['slug'] = SlugHelper::createUniqueSlug($payload['title'], self::class);
        }

        return $this->find($id)->update($payload);
    }

    public function getAll(array $filter, int $page = 1, int $itemPerPage = 0, string $sort = '')
    {

        $skip = ($page * $itemPerPage) - $itemPerPage;
        $projects = $this->query();

        // Filter by title
        if (! empty($filter['title'])) {
            $projects->where('title', 'LIKE', '%' . $filter['title'] . '%');
        }

        // Filter by publication status
        if (isset($filter['is_published'])) {
            $projects->where('is_published', $filter['is_published']);
        }

        $allowedSorts = [
            'title_asc' => 'title ASC',
            'title_desc' => 'title DESC',
            'slug_asc' => 'slug ASC',
            'slug_desc' => 'slug DESC',
            'created_at_asc' => 'created_at ASC',
            'created_at_desc' => 'created_at DESC',
        ];

        $sortKey = str_replace(' ', '_', strtolower($sort));

        if (isset($allowedSorts[$sortKey])) {
            $projects->orderByRaw($allowedSorts[$sortKey]);
        } else {
            $projects->orderBy('created_at', 'asc');
        }

        $total = $projects->count();

        $list = $projects->skip($skip)->take($itemPerPage)->get();

        return [
            'total' => $total,
            'data' => $list,
        ];
    }

    public function getById(string $id)
    {
        return $this->find($id);
    }

    public function store(array $payload)
    {
        // Generate unique slug 
        $payload['slug'] = SlugHelper::createUniqueSlug($payload['title'], self::class);
        // dd($payload);

        return $this->create($payload);
    }
}
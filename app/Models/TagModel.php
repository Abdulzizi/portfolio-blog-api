<?php

namespace App\Models;

use App\Helpers\SlugHelper;
use App\Http\Traits\Uuid;
use App\Repository\CrudInterface;
use Illuminate\Database\Eloquent\Model;

class TagModel extends Model implements CrudInterface
{
    use Uuid;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    protected $table = 'tags';

    public function blogPosts()
    {
        // in blog_tags table we have blog_post_id and tag_id
        return $this->belongsToMany(BlogPostModel::class, 'blog_tags', 'tag_id', 'blog_post_id');
    }

    public function drop(string $id)
    {
        return $this->find($id)->delete();
    }

    public function edit(array $payload, string $id)
    {
        if (!empty($payload['name'])) {
            $payload['slug'] = SlugHelper::createUniqueSlug($payload['name'], self::class);
        }

        return $this->find($id)->update($payload);
    }

    public function getAll(array $filter, int $page = 1, int $itemPerPage = 0, string $sort = '')
    {

        $skip = ($page * $itemPerPage) - $itemPerPage;
        $tag = $this->query();

        // Filter by name
        if (! empty($filter['name'])) {
            $tag->where('name', 'LIKE', '%' . $filter['name'] . '%');
        }

        // Filter by slug
        if (! empty($filter['slug'])) {
            $tag->where('slug', 'LIKE', '%' . $filter['slug'] . '%');
        }

        // Filter by description
        if (! empty($filter['description'])) {
            $tag->where('description', 'LIKE', '%' . $filter['description'] . '%');
        }

        $allowedSorts = [
            'name_asc' => 'name ASC',
            'name_desc' => 'name DESC',
            'slug_asc' => 'slug ASC',
            'slug_desc' => 'slug DESC',
            'created_at_asc' => 'created_at ASC',
            'created_at_desc' => 'created_at DESC',
        ];

        $sortKey = str_replace(' ', '_', strtolower($sort));

        if (isset($allowedSorts[$sortKey])) {
            $tag->orderByRaw($allowedSorts[$sortKey]);
        } else {
            $tag->orderBy('created_at', 'asc');
        }

        $total = $tag->count();

        $list = $tag->skip($skip)->take($itemPerPage)->get();

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
        $payload['slug'] = SlugHelper::createUniqueSlug($payload['name'], self::class);

        return $this->create($payload);
    }
}
<?php

namespace App\Models;

use App\Helpers\SlugHelper;
use App\Http\Traits\Uuid;
use App\Repository\CrudInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPostModel extends Model implements CrudInterface
{
    use SoftDeletes; // Use SoftDeletes library
    use Uuid;

    protected $fillable = [
        'title',
        'content',
        'cover_image',
        'slug',
        'is_published',
    ];

    protected $table = 'blog_posts';

    public function tags()
    {
        return $this->belongsToMany(TagModel::class, 'blog_tags', 'blog_post_id', 'tag_id');
    }

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
        $blogPost = $this->query();

        // Filter by title
        if (! empty($filter['title'])) {
            $blogPost->where('title', 'LIKE', '%' . $filter['title'] . '%');
        }

        // Filter by publication status
        if (isset($filter['is_published'])) {
            $blogPost->where('is_published', $filter['is_published']);
        }

        // Filter by tags (using a relationship check)
        if (! empty($filter['tags'])) {
            $tags = is_array($filter['tags'])
                ? $filter['tags']
                : explode(',', $filter['tags']);

            $blogPost->whereHas('tags', function ($query) use ($tags) {
                // If you really want to filter by tag ID:
                // $query->whereIn('tags.id', $tags);

                // Or, if you intended to filter by slug:
                $query->whereIn('tags.slug', $tags);
            });
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
            $blogPost->orderByRaw($allowedSorts[$sortKey]);
        } else {
            $blogPost->orderBy('created_at', 'asc');
        }

        $total = $blogPost->count();

        $list = $blogPost->skip($skip)->take($itemPerPage)->get();

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

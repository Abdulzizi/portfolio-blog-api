<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use JsonSerializable;

class PostResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'featured_image' => !empty($this->featured_image) ? Storage::disk('public')->url($this->featured_image) : Storage::disk('public')->url('../assets/img/no-image.png'),
            'category_id' => (string) $this->category_id,
            'tags' => $this->tags->pluck('name'),
            'author' => [
                'id' => $this->author->id,
                'name' => $this->author->name,
            ],
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'link' => $this->link,
            'slug' => $this->slug,
            'thumbnail' => $this->thumbnail ? Storage::url($this->thumbnail) : null,
            'images' => is_array($this->images) ? array_map(fn($img) => Storage::url($img), $this->images) : [],
            'tech_stack' => $this->tech_stack ?? [],
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_published' => $this->is_published,
        ];
    }
}

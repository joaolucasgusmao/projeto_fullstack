<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
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
            'slug' => $this->slug,
            'hat' => $this->hat,
            'summary' => $this->summary,
            'image' => $this->image,
            'caption' => $this->caption,
            'topics' => $this->topics ?? [],
            'content' => $this->content,
            'is_fixed' => (bool) $this->is_fixed,
            'is_draft' => (bool) $this->is_draft,
            'is_active' => (bool) $this->is_active,
            'categories' => $this->categories->map(fn($category) => [
                'id' => $category->id,
                'name' => $category->name,
            ]),
            'user' => new UserResource($this->whenLoaded('user')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // 'views' => $this->views
        ];
    }
}

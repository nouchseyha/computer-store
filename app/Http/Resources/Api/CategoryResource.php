<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'slug'           => $this->slug,
            'description'    => $this->description,
            'is_active'      => $this->is_active,
            'products_count' => $this->whenCounted('products'),
            'created_at'     => $this->created_at->toDateTimeString(),
        ];
    }
}

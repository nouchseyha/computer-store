<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'slug'              => $this->slug,
            'short_description' => $this->short_description,
            'description'       => $this->description,
            'price'             => (float) $this->price,
            'sale_price'        => $this->sale_price ? (float) $this->sale_price : null,
            'current_price'     => (float) ($this->sale_price ?? $this->price),
            'stock'             => $this->stock,
            'sku'               => $this->sku,
            'brand'             => $this->brand,
            'image'             => $this->image,
            'is_active'         => $this->is_active,
            'is_featured'       => $this->is_featured,
            'category'          => new CategoryResource($this->whenLoaded('category')),
            'created_at'        => $this->created_at->toDateTimeString(),
        ];
    }
}

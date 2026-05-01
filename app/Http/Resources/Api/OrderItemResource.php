<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'product_name' => $this->product_name,
            'price'        => (float) $this->price,
            'quantity'     => $this->quantity,
            'subtotal'     => (float) $this->subtotal,
        ];
    }
}

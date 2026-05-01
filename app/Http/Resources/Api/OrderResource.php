<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'               => $this->id,
            'order_number'     => $this->order_number,
            'status'           => $this->status,
            'subtotal'         => (float) $this->subtotal,
            'shipping'         => (float) $this->shipping,
            'total'            => (float) $this->total,
            'payment_method'   => $this->payment_method,
            'payment_status'   => $this->payment_status,
            'shipping_name'    => $this->shipping_name,
            'shipping_email'   => $this->shipping_email,
            'shipping_phone'   => $this->shipping_phone,
            'shipping_address' => $this->shipping_address,
            'shipping_city'    => $this->shipping_city,
            'notes'            => $this->notes,
            'items'            => OrderItemResource::collection($this->whenLoaded('items')),
            'user'             => new UserResource($this->whenLoaded('user')),
            'created_at'       => $this->created_at->toDateTimeString(),
        ];
    }
}

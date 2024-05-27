<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $imageUrls = [];
        foreach ($this->product->images as $image) {
            $imageUrl = env("BASE_URL") . Storage::url($image);
            $imageUrls[] = $imageUrl;
        }
        return [
            "cart" => $this->uuid,
            'quantity' => $this->quantity,
            'name' => $this->product->name,
            'uuid' => $this->product->uuid,
            'category' => $this->product->category,
            'price' => $this->product->price,
            'description' => $this->product->description,
            'price_type' => $this->product->price_type,
            'condition' => $this->product->condition,
            'delivery_details' => $this->product->delivery_details,
            'quantity' => $this->product->quantity,
            'images' => $imageUrls,
            'location' => $this->product->location,
            'store' => $this->product->store->name,
            "store_uuid" => $this->product->store->uuid,
            'rate' => $this->product->rate,
        ];
    }
}

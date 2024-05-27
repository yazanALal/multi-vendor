<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MyStoreProductResource extends JsonResource
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
        foreach ($this->images as $image) {
            $imageUrl = env("BASE_URL") . Storage::url($image);
            $imageUrls[] = $imageUrl;
        }

        return [
            'name' => $this->name,
            'uuid' => $this->uuid,
            'category' => $this->category,
            'price' => $this->price,
            'description' => $this->description,
            'price_type' => $this->price_type,
            'condition' => $this->condition,
            'delivery_details' => $this->delivery_details,
            'quantity' => $this->quantity,
            'images' => $imageUrls,
            'location' => $this->location,
            'rate' => $this->rate
        ];
    }
}

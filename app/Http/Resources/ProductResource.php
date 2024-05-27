<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
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
            $imageUrl = env("BASE_URL").Storage::url($image);
            $imageUrls[] = $imageUrl;
        }

        return [
            'name'=> $this->name,
            'uuid'=>$this->uuid,
            'category'=> $this->category,
            'price'=> $this->price,
            'sales_price'=> $this->sales_price,
            'description'=> $this->description,
            'price_type'=> $this->price_type,
            'condition'=> $this->condition,
            'delivery_details'=> $this->delivery_details,
            'images'=> $imageUrls,
            'location'=>$this->location,
            'store'=> $this->store->name,
            "store_uuid"=> $this->store->uuid,
            'rate'=> $this->rate,
            'quantity' => $this->quantity,
            
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserStoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if (is_null($this->image)) {
            return [
                'name' => $this->name,
                'image' => null,
                'followers' => $this->followers,
                'total_products' => count($this->products),
                'products' => MyStoreProductResource::collection($this->products),
                
            ];
        }
        return [
            'name' => $this->name,
            'image' => env("BASE_URL") . Storage::url($this->image),
            'followers' => $this->followers,
            'total_products' => count($this->products), 
            'products' => MyStoreProductResource::collection($this->products),
        ];
    }
}

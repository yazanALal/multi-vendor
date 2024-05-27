<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MyStoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if(is_null($this->store->image)){
            return [
                'name' => $this->store->name,
                'image' => null,
                'followers' => $this->store->followers,
                'products' => MyStoreProductResource::collection($this->store->products),
                'total_products' => count($this->store->products),
            ];
        }
        return [
            'name'=>$this->store->name,
            'image'=>env("BASE_URL").Storage::url($this->store->image),
            'followers'=> $this->store->followers,
            'products'=>MyStoreProductResource::collection($this->store->products),
            'total_products'=>count($this->store->products),
        ];
    }
}

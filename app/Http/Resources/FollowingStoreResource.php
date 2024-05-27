<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class FollowingStoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if (is_null($this->store->image)) {
            return [
                'name' => $this->store->name,
                'image' => null,
                'uuid' => $this->store->uuid,
            ];
        }
        return [
            'name' => $this->store->name,
            'image' => env("BASE_URL") . Storage::url($this->store->image),
            'uuid' => $this->store->uuid,
        ];
    }
}

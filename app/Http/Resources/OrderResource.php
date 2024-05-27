<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'uuid'=>$this->uuid,
            'address'=>$this->address,
            'created_at'=>$this->created_at->diffForHumans(),
            'total_price'=>$this->total_price,
            "user" => $this->user->first_name . " " . $this->user->last_name,
            'status' => $this->status,
            'order_items'=>OrderItemsResource::collection($this->orderItems),
        ];
    }
}

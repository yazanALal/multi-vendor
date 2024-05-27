<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            "user"=>$this->user->first_name." " .$this->user->last_name,
            "rate"=>$this->rate,
            "comment"=>$this->comment,
            "created"=>$this->created_at->diffForHumans(),
        ];
    }
}

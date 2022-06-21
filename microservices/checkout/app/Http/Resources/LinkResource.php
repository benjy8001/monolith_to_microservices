<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Microservices\UserService;

class LinkResource extends JsonResource
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
            'id' => $this->id,
            'code' => $this->code,
            'user' => (new UserService())->get($this->user_id),
            'products' => ProductResource::collection($this->products),
        ];
    }
}

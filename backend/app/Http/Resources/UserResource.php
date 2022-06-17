<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
        ];

        if ($this->isInfluencer()) {
            $data['revenue'] = $this->revenue();
        }

        if ($this->isAdmin()) {
            $data['permissions'] = $this->permissions();
            $data['role'] = $this->role();
        }

        return $data;
    }
}

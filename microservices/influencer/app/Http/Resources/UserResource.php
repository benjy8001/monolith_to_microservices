<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;

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
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'revenue' => $this->revenue($this->id),
        ];
    }

    /**
     * @return int
     */
    public function revenue(int $userId): int
    {
        $orders = Order::where('user_id', $userId)->get();
        return $orders->sum(fn (Order $order) => $order->total);
    }
}

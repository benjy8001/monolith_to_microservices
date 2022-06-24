<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Order;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;
use Microservices\UserService;

class StatsController
{
    private $userService;

    /**
     * AuthController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @return Collection
     */
    public function index(): Collection
    {
        $user = $this->userService->getUser();

        $links = Link::where('user_id', $user->id)->get();

        return $links->map(function (Link $link) {
            $orders = Order::where('code', $link->code)->get();

            return [
                'code' => $link->code,
                'count' => $orders->count(),
                'revenue' => $orders->sum(function (Order $order) {
                    return round($order->influencer_total, 2);
                }),
            ];
        });
    }

    /**
     * @return array
     */
    public function rankings(): array
    {
        return Redis::zrevrange('rankings', 0, -1, 'WITHSCORES');
    }
}

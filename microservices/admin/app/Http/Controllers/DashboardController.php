<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChartResource;
use App\Models\Order;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Microservices\UserService;

class DashboardController
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
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function chart(): AnonymousResourceCollection
    {
        $this->userService->allows('view', 'orders');

        return ChartResource::collection(Order::query()
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->selectRaw('DATE_FORMAT(orders.created_at, "%Y-%m-%d") AS date, SUM(order_items.quantity * order_items.price) AS sum')
            ->groupBy('date')
            ->get());
    }
}

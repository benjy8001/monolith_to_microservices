<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return OrderResource::collection(Order::paginate());
    }

    /**
     * @param int $id
     *
     * @return OrderResource
     */
    public function show(int $id): OrderResource
    {
        return new OrderResource(Order::find($id));
    }
}

<?php

namespace App\Http\Controllers\Influencer;

use App\Models\Link;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class StatsController
{
    /**
     * @param Request $request
     * @return Collection
     */
    public function index(Request $request): Collection
    {
        $user = $request->user();

        $links = Link::where('user_id', $user->id)->get();

        return $links->map(function (Link $link) {
            $orders = Order::where('code', $link->code)->where('complete', 1)->get();

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

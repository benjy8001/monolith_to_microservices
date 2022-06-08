<?php

namespace App\Listeners;

use App\Events\OrderCompletedEvent;
use App\Models\User;
use Illuminate\Support\Facades\Redis;

class UpdateRankingsListener
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(OrderCompletedEvent $event)
    {
        $order = $event->order;
        $revenue = $order->influencer_total;
        $user = User::find($order->user_id);

        Redis::zincrby('rankings', $revenue, $user->full_name);
    }
}

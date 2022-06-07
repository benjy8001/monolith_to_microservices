<?php

namespace App\Listeners;

use App\Events\OrderCompletedEvent;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

class NotifyInfluencerListener
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
        Mail::send('mails/influencer/influencer', ['order' => $order], function (Message $message) use ($order) {
            $message->to($order->influencer_email);
            $message->subject('A new order has been completed!');
        });
    }
}

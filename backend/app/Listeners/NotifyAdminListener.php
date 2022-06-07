<?php

namespace App\Listeners;

use App\Events\OrderCompletedEvent;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

class NotifyAdminListener
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
        Mail::send('mails/influencer/admin', ['order' => $order], function (Message $message) {
            $message->to('admin@admin.com');
            $message->subject('A new order has been completed!');
        });
    }
}

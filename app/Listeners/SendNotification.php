<?php

namespace App\Listeners;

use App\Events\OrderCreatedEvent;
use App\Models\Order;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\OrderCreatedEvent  $event
     * @return void
     */
    public function handle(OrderCreatedEvent $event)
    {
        $orderIds=$event->orderIds;
        $orders=Order::whereIn('id',$orderIds)->with(['store','store.user','user'])->get();
        foreach ($orders as $order){
           $order->store->user->notify(new OrderCreatedNotification($order));
        }
    }
}

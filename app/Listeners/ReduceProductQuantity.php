<?php

namespace App\Listeners;

use App\Events\OrderCreatedEvent;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ReduceProductQuantity
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
        $cartItems = $event->cartItems;
        foreach ($cartItems as $item){
            Product::where("id",$item->product_id)->decrement('quantity', $item->quantity);
        }
        

        // Rest of the listener logic...
    }
}

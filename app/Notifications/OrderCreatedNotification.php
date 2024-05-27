<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification
{
    use Queueable;
    public $order;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database','broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        return (new MailMessage)
            ->subject('Order created')
            ->line('New Order created from ' . $this->order->user->first_name . ' ' . $this->order->user->last_name);
    }

    public function toDatabase($notifiable)
    {
        
        return [
            'body' => 'New Order created from ' .  $this->order->user->first_name . ' ' . $this->order->user->last_name,
            
        ];
    }

    public function toBroadcast($notifiable)
    {
        
        return new BroadcastMessage([
            'body' => 'New Order created from ' .  $this->order->user->first_name . ' ' . $this->order->user->last_name,

        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */


    public function toArray($notifiable)
    {
        
    }
}

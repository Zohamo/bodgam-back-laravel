<?php

namespace App\Notifications;

use App\Events\UserEventSubscriptionEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserEventSubscription extends Notification // implements ShouldQueue, ShouldBroadcast
{
    // use Queueable;

    protected $dataToNotify;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($dataToNotify)
    {
        $this->dataToNotify = $dataToNotify;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->dataToNotify;
    }
}

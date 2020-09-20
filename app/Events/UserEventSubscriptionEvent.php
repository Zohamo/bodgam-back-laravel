<?php

namespace App\Events;

use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserEventSubscriptionEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $destId;
    public $id;
    public $data;
    public $type;
    public $notifiable_type;
    public $created_at;
    public $updated_at;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $destId, $data, $notificationId)
    {
        $this->destId = $destId;
        $this->id = $notificationId;
        $this->data = $data;
        $this->type = "App\Notifications\UserEventSubscription";
        $this->notifiable_type = "App\Profile";
        $this->created_at = Carbon::now();
        $this->updated_at = Carbon::now();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('user-notifications');
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return "user-$this->destId";
    }
}

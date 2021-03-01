<?php

namespace App\Events;

use App\Event;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EventEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $eventId;
    public $data;
    public $type;
    public $notifiable_type;

    /**
     * Creates a new EventEvent instance.
     *
     * @param int $eventId
     * @param mixed $event
     * @return void
     */
    public function __construct(int $eventId, $event)
    {
        // $this->eventId = $data['id'];
        $this->eventId = $eventId;
        $this->data = $event;
        $this->type = "App\Notifications\Event";
        $this->notifiable_type = "App\Event";
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('event-notifications');
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return "event-$this->eventId";
    }
}

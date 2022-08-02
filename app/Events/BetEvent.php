<?php

namespace App\Events;

use App\Models\Bet;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BetEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private Bet $bet;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Bet $bet)
    {
        $this->bet = $bet;
    }

    public function broadcastWith()
    {
        return [
            'user' => $this->bet->user->name,
            'color' => $this->bet->color,
            'value' => $this->bet->value
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('roulette');
    }
}

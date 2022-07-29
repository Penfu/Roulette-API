<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Bet implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private string $gambler;
    private int $amount;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($gambler, $amount)
    {
        $this->gambler = $gambler;
        $this->amount = $amount;
    }

    public function broadcastWith()
    {
        return [
            'gambler' => $this->gambler,
            'amount' => $this->amount
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

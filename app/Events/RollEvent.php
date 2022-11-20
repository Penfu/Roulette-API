<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use app\Models\Roll;

class RollEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private string $status;
    private int $timer;
    private array $bets;
    private ?Roll $result;

    public function __construct(string $status, int $timer, array $bets, ?Roll $result = null)
    {
        $this->status = $status;
        $this->timer = $timer;
        $this->bets = $bets;
        $this->result = $result;
    }

    public function broadcastWith()
    {
        return [
            'status' => $this->status,
            'timer' => $this->timer,
            'bets' => $this->bets,
            'result' => $this->result
                ? [ 'value' => $this->result->value, 'color' => $this->result->color]
                : null,
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

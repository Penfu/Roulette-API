<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use app\Models\Roll;

class RollEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        protected string $status,
        protected int $timer = 0,
        protected array $bets = [],
        protected array $history = [],
        protected ?Roll $result = null
    ) { }

    public function broadcastWith()
    {
        return [
            'status' => $this->status,
            'timer' => $this->timer,
            'bets' => $this->bets,
            'history' => $this->history,
            'result' => $this->result ? [
                'value' => $this->result->value,
                'color' => $this->result->color,
            ] : null,
        ];
    }

    public function broadcastOn()
    {
        return new Channel('roulette');
    }
}

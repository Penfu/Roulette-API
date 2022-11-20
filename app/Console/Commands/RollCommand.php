<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Roll;

use App\Events\RollEvent;

enum Status
{
    case OPEN;
    case CLOSE;
    case RESULT;

    public function value(): string
    {
        return match ($this) {
            Status::OPEN => 'OPEN',
            Status::CLOSE => 'CLOSE',
            Status::RESULT => 'RESULT',
        };
    }
}

class RollCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    const OPEN_BET_DURATION = 15000; // ms
    const BROADCAST_FREQUENCY = 500; // ms


    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Open bet
        $timer = 15000; // ms

        do {
            broadcast(new RollEvent(Status::OPEN->value(), $timer, []));

            usleep(self::BROADCAST_FREQUENCY * 1000);
            $timer -= self::BROADCAST_FREQUENCY;
        } while ($timer > 0);

        // Close bet
        $timer = 5000; // ms

        $colors = ['red', 'black', 'green'];
        $roll = Roll::create([
            'color' => $colors[array_rand($colors)],
            'value' => rand(1, 13),
        ]);

        do  {
            broadcast(new RollEvent(Status::CLOSE->value(), $timer, [], $roll));

            usleep(self::BROADCAST_FREQUENCY * 1000);
            $timer -= self::BROADCAST_FREQUENCY;
        } while($timer > 0);

        // Result
        $timer = 5000; // ms

        do {
            broadcast(new RollEvent(Status::RESULT->value(), $timer, [], $roll));

            usleep(self::BROADCAST_FREQUENCY * 1000);
            $timer -= self::BROADCAST_FREQUENCY;
        } while($timer > 0);
    }
}

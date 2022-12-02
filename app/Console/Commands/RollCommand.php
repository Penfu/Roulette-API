<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Cache;
use Illuminate\Console\Command;

use App\Events\RollEvent;
use App\Models\User;
use App\Models\Roll;

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

    const BROADCAST_FREQUENCY = 500; // ms
    const OPEN_BET_DURATION   = 15000; // ms
    const ROLL_DURATION       = 5000; // ms
    const RESULT_DURATION     = 5000; // ms

    const CASES = [
        ['value' => 1, 'color' => 'red'],
        ['value' => 2, 'color' => 'black'],
        ['value' => 3, 'color' => 'red'],
        ['value' => 4, 'color' => 'black'],
        ['value' => 5, 'color' => 'red'],
        ['value' => 6, 'color' => 'black'],
        ['value' => 7, 'color' => 'red'],
        ['value' => 8, 'color' => 'black'],
        ['value' => 9, 'color' => 'red'],
        ['value' => 10, 'color' => 'black'],
        ['value' => 11, 'color' => 'red'],
        ['value' => 12, 'color' => 'black'],
        ['value' => 13, 'color' => 'green'],
     ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $roll = Roll::create();

        Cache::put('roll_id', $roll->id);
        Cache::forget('bets');

        // Open bet
        $this->broadcastLoop(Status::OPEN, self::OPEN_BET_DURATION);

        // Close bet
        $rndRoll = self::CASES[random_int(1, count(self::CASES)) - 1];
        $roll->color = $rndRoll['color'];
        $roll->value = $rndRoll['value'];

        $this->broadcastLoop(Status::CLOSE, self::ROLL_DURATION, $roll);

        // Result
        $roll->save();
        $bets = Cache::get('bets', ['red' => [], 'black' => [], 'green' => []]);
        $winningBets = $bets[$roll->color];

        foreach ($winningBets as $bet) {
            $user = User::where('name', $bet['user'])->first();
            $user->balance += $bet['value'] * 2;
            $user->save();
        }

        $this->broadcastLoop(Status::RESULT, self::RESULT_DURATION, $roll);
    }

    private function broadcastLoop(Status $status, int $timer, ?Roll $roll = null)
    {
        do {
            $bets = $status == Status::RESULT
                ? []
                : Cache::get('bets', ['red' => [], 'black' => [], 'green' => []]);

            broadcast(new RollEvent($status->value(), $timer, $bets, $roll));

            usleep(self::BROADCAST_FREQUENCY * 1000);
            $timer -= self::BROADCAST_FREQUENCY;
        } while ($timer > 0);
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Cache;
use Illuminate\Console\Command;

use App\Events\RollEvent;
use App\Helpers\ColorHelper;
use App\Models\User;
use App\Models\Roll;

enum Step
{
    case BET;
    case ROLL;
    case ROLL_TO_RESULT;
    case DISPLAY_RESULT;

    public function value(): string
    {
        return match ($this) {
            Step::BET => 'BET',
            Step::ROLL => 'ROLL',
            Step::ROLL_TO_RESULT => 'ROLL_TO_RESULT',
            Step::DISPLAY_RESULT => 'DISPLAY_RESULT',
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

    // Duration of each step in ms
    const BROADCAST_FREQUENCY = 500;

    const BET_DURATION = 15000;
    const ROLL_DURATION = 4000;
    const ROLL_TO_RESULT_DURATION = 2000;
    const DISPLAY_RESULT_DURATION = 5000;

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
        // Create roll
        $roll = Roll::create();
        Cache::put('roll_id', $roll->id);

        // Step 1: Open bet
        $this->broadcastLoop(Step::BET, self::BET_DURATION);

        // Step 2: Close the bet, Roll the wheel
        $this->broadcastLoop(Step::ROLL, self::ROLL_DURATION);

        // Step 3: Generate the result, Roll to result
        $rndRoll = self::CASES[random_int(1, count(self::CASES)) - 1];
        $roll->color = $rndRoll['color'];
        $roll->value = $rndRoll['value'];

        $roll->ended_at = now();
        $roll->save();

        $this->broadcastLoop(Step::ROLL_TO_RESULT, self::ROLL_TO_RESULT_DURATION, $roll);

        // Step 4: Display the result
        $bets = Cache::get('bets', ['red' => [], 'black' => [], 'green' => []]);
        $wins = $bets[$roll->color];

        foreach ($wins as $bet) {
            $user = User::where('name', $bet['user'])->first();
            $user->balance += $bet['amount'] * ColorHelper::MULTIPLIERS[$roll->color];
            $user->save();
        }

        $this->broadcastLoop(Step::DISPLAY_RESULT, self::DISPLAY_RESULT_DURATION, $roll);

        // Reset
        Cache::forget('bets');
    }

    private function broadcastLoop(Step $status, int $timer, ?Roll $roll = null)
    {
        do {
            $bets = Cache::get('bets', ['red' => [], 'black' => [], 'green' => []]);

            broadcast(new RollEvent($status->value(), $timer, $bets, $roll));

            usleep(self::BROADCAST_FREQUENCY * 1000);
            $timer -= self::BROADCAST_FREQUENCY;
        } while ($timer >= 0);
    }
}

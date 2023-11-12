<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Cache;
use Illuminate\Console\Command;

use App\Events\RollEvent;

use App\Helpers\CaseHelper;
use App\Helpers\ColorHelper;

use App\Models\User;
use App\Models\Roll;

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
    const ROLL_DURATION = 5000;
    const DISPLAY_RESULT_DURATION = 5000;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Create roll
        $roll = Roll::create();
        Cache::put('roll_id', $roll->id);

        // Step 1: Open bet
        $this->broadcastLoop('BET', self::BET_DURATION);


        // Step 2: Generate the result,
        $rndRoll = CaseHelper::random();
        $roll->color = $rndRoll['color'];
        $roll->value = $rndRoll['value'];

        $roll->ended_at = now();
        $roll->save();

        // Step 3: Roll the wheel
        $this->broadcastLoop('ROLL', self::ROLL_DURATION, $roll);

        // Step 4: Display the result
        $bets = Cache::get('bets', ['red' => [], 'black' => [], 'green' => []]);
        $wins = $bets[$roll->color];

        foreach ($wins as $bet) {
            $user = User::where('name', $bet['user'])->first();
            $user->balance += $bet['amount'] * ColorHelper::MULTIPLIERS[$roll->color];
            $user->save();
        }

        $this->broadcastLoop('DISPLAY_RESULT', self::DISPLAY_RESULT_DURATION, $roll);

        // Reset
        Cache::forget('bets');
    }

    private function broadcastLoop(string $status, int $timer, ?Roll $roll = null)
    {
        $history = Roll::ended()
            ->latest()
            ->take(10)
            ->get()
            ->map(fn($roll) => ['value' => $roll->value, 'color' => $roll->color])
            ->toArray();

        do {
            $bets = Cache::get('bets', ['red' => [], 'black' => [], 'green' => []]);

            broadcast(new RollEvent($status, $timer, $bets, $history, $roll));

            usleep(self::BROADCAST_FREQUENCY * 1000);
            $timer -= self::BROADCAST_FREQUENCY;
        } while ($timer >= 0);
    }
}

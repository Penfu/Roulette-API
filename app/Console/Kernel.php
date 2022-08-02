<?php

namespace App\Console;

use App\Events\BetEvent;
use App\Models\Bet;
use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        // Send a bet event to the roulette channel every 30 seconds
        $schedule->call(function () {
            $bet = Bet::create([
                'user_id' => 1,
                'color' => 'red',
                'value' => rand(1, 36),
            ]);

            broadcast(new BetEvent($bet))->toOthers();
        })->cron('*/1 * * * *');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

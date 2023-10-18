<?php

namespace Database\Seeders;

use App\Models\Bet;
use Illuminate\Database\Seeder;

class BetSeeder extends Seeder
{
    public function run()
    {
        Bet::factory()->count(random_int(500, 1000))->create();
    }
}

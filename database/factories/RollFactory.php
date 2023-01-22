<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

use App\Models\Roll;

class RollFactory extends Factory
{
    public function definition()
    {
        $createdAt = Carbon::instance($this->faker->dateTimeBetween('-1 month', 'now'))->subSeconds(30 * Roll::count());
        $endedAt = $createdAt->copy()->addSeconds(30);

        return [
            'color' => $this->faker->randomElement(['red', 'black', 'green']),
            'value' => random_int(1, 13),
            'created_at' => $createdAt,
            'updated_at' => $endedAt,
            'ended_at' => $endedAt,
        ];
    }
}

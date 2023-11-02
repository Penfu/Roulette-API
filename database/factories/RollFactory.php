<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

use App\Models\Roll;

class RollFactory extends Factory
{
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

    public function definition()
    {
        $case = self::CASES[random_int(0, 12)];

        $createdAt = Carbon::instance($this->faker->dateTimeBetween('-1 month', 'now'))->subSeconds(30 * Roll::count());
        $endedAt = $createdAt->copy()->addSeconds(30);

        return [
            'color' => $case['color'],
            'value' => $case['value'],
            'created_at' => $createdAt,
            'updated_at' => $endedAt,
            'ended_at' => $endedAt,
        ];
    }
}

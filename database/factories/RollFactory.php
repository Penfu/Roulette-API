<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

use App\Helpers\CaseHelper;

use App\Models\Roll;

class RollFactory extends Factory
{
    public function definition()
    {
        $case = CaseHelper::random();
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

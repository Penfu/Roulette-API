<?php

namespace Database\Factories;

use App\Helpers\ColorHelper;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use App\Models\Roll;
use App\Models\User;

class BetFactory extends Factory
{
    public function definition()
    {
        $user = User::where('balance', '>=', 1)
            ->inRandomOrder()
            ->first();
        $roll = Roll::inRandomOrder()->first();

        $win = $this->faker->boolean(30); // Without, there to much lose as the odds are 1/3
        $colors = new Collection(['red', 'black', 'green']);
        $color = $win ? $roll->color : $this->faker->randomElement($colors->except($roll->color));
        $amount = random_int(1, $user->balance);
        $date = $this->faker->dateTimeBetween($roll->created_at, $roll->ended_at);

        $user->balance -= $amount;
        if ($color === $roll->color) {
            $user->balance += $amount * ColorHelper::MULTIPLIERS[$color];
        }
        $user->save();

        return [
            'color' => $color,
            'amount' => $amount,
            'user_id' => $user->id,
            'roll_id' => $roll->id,
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}

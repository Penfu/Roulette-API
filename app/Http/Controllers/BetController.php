<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Bet;

class BetController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $amount = $request->input('amount');
        $color = $request->input('color');
        $user = $request->user();

        // Conditions
        if ($user->balance < $amount) {
            return response()->json(['message' => 'Insufficient funds'], 400);
        }

        // Get bets for the current roll
        $roll = Cache::get('roll_id');
        $bets = Cache::get('bets', ['red' => [], 'black' => [], 'green' => []]);

        // Update or create the bet in the database
        $bet = Bet::firstOrNew(['user_id' => $user->id, 'roll_id' => $roll, 'color' => $color]);
        $bet->amount += $amount;
        $bet->save();

        // Update or create the bet in the cache
        $betExists = false;

        foreach ($bets[$color] as $key => $bet) {
            if ($bet['user'] == $user->name) {
                $bets[$color][$key]['amount'] += $amount;
                $betExists = true;
            }
        }

        if (!$betExists) {
            $bets[$color][] = [
                'amount' => $amount,
                'user' => $user->name,
            ];
        }

        Cache::put('bets', $bets);

        $user->balance -= $amount;
        $user->save();
    }

    public function getRoll(Bet $bet)
    {
        return $bet->roll;
    }
}

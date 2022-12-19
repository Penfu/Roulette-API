<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Bet;
use App\Models\User;

class BetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $color = $request->input('color');
        $value = $request->input('value');

        // Conditions
        if ($user->balance < $value) {
            return response()->json(['message' => 'Insufficient funds'], 400);
        }

        // Get bets for the current roll
        $roll = Cache::get('roll_id');
        $bets = Cache::get('bets', ['red' => [], 'black' => [], 'green' => []]);

        // Update or create the bet in the database
        $bet = Bet::firstOrNew(['user_id' => $user->id, 'roll_id' => $roll, 'color' => $color]);
        $bet->value += $value;
        $bet->save();

        // Update or create the bet in the cache
        $betExists = false;

        foreach ($bets[$color] as $key => $bet) {
            if ($bet['user'] == $user->name) {
                $bets[$color][$key]['value'] += $value;
                $betExists = true;
            }
        }

        if (!$betExists) {
            $bets[$color][] = [
                'value' => $value,
                'user' => $user->name,
            ];
        }

        Cache::put('bets', $bets);

        $user->balance -= $value;
        $user->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

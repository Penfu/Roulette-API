<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    // Returns user's bets with result
    public function bets(User $user)
    {
        $bets = $user->bets()->with('roll')->get();
        return response()->json($bets);
    }

    public function stats(User $user)
    {
        return response()->json($user->stats());
    }
}

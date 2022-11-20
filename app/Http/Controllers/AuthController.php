<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class AuthController extends Controller
{
    public function authenticate(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = User::where('email', $credentials['email'])->first();

            return response()->json([
                'user' => $user,
                'token' => $user->createToken('token')->plainTextToken,
            ]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}

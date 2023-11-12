<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;

use App\Http\Requests\UserStoreRequest;
use App\Models\User;

class AuthController extends Controller
{
    public function register(UserStoreRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'balance' => 1000,
        ]);

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('token')->plainTextToken,
        ]);
    }

    public function login(Request $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user->password) {
            return response()->json(['message' => 'User have no password'], Response::HTTP_UNAUTHORIZED);
        }

        if ($user->provider) {
            return response()->json(['message' => 'User have a provider'], Response::HTTP_UNAUTHORIZED);
        }

        if ($user->provider || !Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('token')->plainTextToken,
        ]);
    }
}

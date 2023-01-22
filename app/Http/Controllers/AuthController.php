<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'balance' => 1000,
        ]);

        return response()->json([
            'token' => $user->createToken('token')->plainTextToken,
        ]);
    }

    public function login(Request $request): JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('token')->plainTextToken,
        ]);
    }
}

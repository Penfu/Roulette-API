<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Models\User;

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

    public function updateAvatar(Request $request)
    {
        $user = $request->user();

        $user->update(['avatar' => $request->avatar]);

        return response()->json($user);
    }

    public function updateName(Request $request)
    {
        $user = $request->user();

        $request->validate(['name' => 'required|string|max:12|unique:users']);
        $user->update($request->only('name'));

        return response()->json($user);
    }

    public function updateEmail(Request $request)
    {
        $user = $request->user();

        if (!$request->password) {
            return response()->json(['message' => 'Password is required'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!password_verify($request->password, $user->password)) {
            return response()->json(['message' => 'Password is incorrect'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $request->validate(['email' => 'required|email|unique:users']);
        $user->update($request->only('email'));

        return response()->json($user);
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        if (!$request->password) {
            return response()->json(['message' => 'Password is required'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!password_verify($request->password, $user->password)) {
            return response()->json(['message' => 'Password is incorrect'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $request->validate(['new_password' => 'required|string|min:8|confirmed']);
        $user->update(['password' => bcrypt($request->new_password)]);

        return response()->json($user);
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        if (!$request->keyword) {
            return response()->json(['message' => 'User name is required'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($request->keyword !== $user->name) {
            return response()->json(['message' => 'User name is incorrect'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted']);
    }

    public function bets(User $user)
    {
        $offset = request()->query('offset', 0);
        $limit = request()->query('limit', 10);

        $bets = $user->bets()->with('roll')->offset($offset)->limit($limit)->latest()->get();

        return response()->json($bets);
    }

    public function stats(User $user)
    {
        return response()->json($user->stats());
    }
}

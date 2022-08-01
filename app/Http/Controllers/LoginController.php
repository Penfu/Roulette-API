<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            Log::debug('Auth Success', $credentials);
            return response(Auth::user(), 200);
        }

        Log::debug('Auth Fail', $credentials);
        abort('401', 'login-failed');
    }

    public function logout(): Response
    {
        Auth::logout();
        return response(null, 204);
    }
}

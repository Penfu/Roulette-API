<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirectToProvider(string $provider)
    {
        $url = Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();

        return response()->json(['redirect' => $url]);
    }

    public function handleProviderCallback(string $provider)
    {
        $providerUser = Socialite::driver($provider)->stateless()->user();

        if (!$providerUser->token) {
            return response()->json(["message" => 'Failed to login'], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::firstOrCreate(
            ['email' => $providerUser->getEmail()],
            [
                'name' => $providerUser->getName(),
                'password' => bcrypt($providerUser->getId()),
                'balance' => 1000
            ]
        );

        $user->oauthProviders()->updateOrCreate(
            ['provider' => $provider],
            [
                'provider_user_id' => $providerUser->getId(),
                'user_id' => $user->id,
            ]
        );

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('token')->plainTextToken,
        ]);
    }
}

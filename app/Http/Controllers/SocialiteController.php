<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Models\User;
use App\Models\OauthProvider;

use App\Mail\AccountUnlinkedFromProvider;

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
                'password' => null,
                'balance' => 1000
            ]
        );

        OauthProvider::updateOrCreate([
            'user_id' => $user->id,
        ], [
            'name' => $provider,
            'provider_user_id' => $providerUser->getId(),
        ]);

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('token')->plainTextToken,
        ]);
    }

    public function unlinkProvider(Request $request)
    {
        $user = $request->user();
        $provider = $user->provider;

        if (!$provider) {
            return response()->json(['message' => 'No provider linked'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $password = bin2hex(random_bytes(16));
        $user->password = bcrypt($password);
        $user->save();

        OauthProvider::where('user_id', $user->id)->delete();

        Mail::to("sypenfu@gmail.com")->send(new AccountUnlinkedFromProvider($user, $provider, $password));

        return response()->json($user);
    }
}

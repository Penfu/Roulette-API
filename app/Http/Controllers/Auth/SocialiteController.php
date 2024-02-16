<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;

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

    public function handleProviderCallback(Request $request, string $provider)
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

        OauthProvider::updateOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $provider,
                'provider_user_id' => $providerUser->getId()
            ]
        );

        Auth::login($user);
        $request->session()->regenerate();

        return response()->noContent();
    }

    public function unlinkProvider(Request $request)
    {
        $user = $request->user();
        $provider = $user->provider;

        if (!$provider) {
            return response()->json(['message' => 'No provider linked'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $password = bin2hex(random_bytes(16));
        $user->password = Hash::make($password);
        $user->save();

        OauthProvider::where('user_id', $user->id)->delete();

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Mail::to("sypenfu@gmail.com")->send(new AccountUnlinkedFromProvider($user, $provider, $password));

        return response()->noContent();
    }
}

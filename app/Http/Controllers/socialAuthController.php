<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;

class socialAuthController extends Controller
{


    public function Callback($provider){
       /* Socialite::driver($provider)->stateless();

        $provider = config('auth.guards.api.provider');
        $userSocial =   Socialite::driver($provider)->stateless()->user();
        $users       =   User::where(['email' => $userSocial->getEmail()])->first();
        if($users){
            Auth::login($users);
            return redirect('/');
        }else{
            $user = User::create([
                'firstname'          => $userSocial->getName(),
                'lastname'          => $userSocial->getName(),
                //  'email'         => $userSocial->getEmail(),
                // 'password'      => "default",
                'provider_id'   => $userSocial->getId(),
                'provider'      => $provider,
            ]);
            return redirect()->route('home');
        }*/
            $user = Socialite::with ( $provider )->user ();
          //  return view ( 'welcome' )->withDetails ( $user )->withService ( $provider );
        return response()->json([
            'success' => '1',
            'code' => '201',
            'message' => 'login with your google account successfully',
            'error' => null,
            'data' => [
                'user' => $user,
                'token_type' => $response->token_type,
                'access_token' => $response->access_token,
                'refresh_token' => $response->refresh_token,
                'expires_in' => $response->expires_in,
            ]
        ]);
        }



    public function handleProviderCallback($provider)
    {

        Socialite::driver($provider)->stateless();
        $user = Socialite::driver($provider)->user();

        $authUser = $this->findOrCreateUser($user, $provider);
        Auth::login($authUser, true);
       // return redirect($this->redirectTo);
         return response()->json([
             'success' => '1',
             'code' => '201',
             'message' => 'login with your google account successfully',
             'error' => null,
             'data' => [
                 'user' => $user,
                 'token_type' => $response->token_type,
                 'access_token' => $response->access_token,
                 'refresh_token' => $response->refresh_token,
                 'expires_in' => $response->expires_in,
             ]
         ]);

        /*try {


            $googleUser = Socialite::driver('google')->user();
            $existUser = User::where('email',$googleUser->email)->first();


            if($existUser) {
                Auth::loginUsingId($existUser->id);
            }
            else {
                $user = new User;
                $user->name = $googleUser->name;
                $user->email = $googleUser->email;
                $user->google_id = $googleUser->id;
                $user->password = md5(rand(1,10000));
                $user->save();
                Auth::loginUsingId($user->id);
            }
            return redirect()->to('/home');
        }
        catch (Exception $e) {
            return 'error';
        }*/

    }

    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function findOrCreateUser($user, $provider)
    {
        $authUser = User::where('provider_id', $user->id)->first();
        if ($authUser) {
            return $authUser;
        }
        return User::create([
            'firstname'     => $user->name,
            // 'name'     => $user->lastname,
            'email'    => $user->email,
            'provider' => $provider,
            'provider_id' => $user->id
        ]);
    }

}

<?php

namespace App\Http\Controllers;

use App\Plan;
use App\User;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function authenticate(Request $request)
    {
        /*  $credentials_login_mail = $request->only('email', 'password');
         if ($provider = "facebook") $credentials_login_provider = $request->only( 'facebook_id');
         elseif ($provider ="google")  $credentials_login_provider = $request->only( 'google_id');

          try {
              if (! $token = JWTAuth::attempt($credentials_login_provider ) ) {
                  return response()->json(['error' => 'invalid_credentials 1'], 400);
               //   else ($token = JWTAuth::attempt ($credentials_login_provider)){
                 //     return response()->json(['error' => 'invalid_credentials 2'], 400);

                  //}
        catch
            (JWTException $e) {
                return response()->json(['error' => 'could_not_create_token'], 500);
            }

        return response()->json(compact('user', 'token'));
    }
              }*/
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|max:255|unique:users',
            'password' => 'string|nullable',
            'provider' => 'in:facebook,google|required_without:password|nullable',
            'facebook_id' => 'string|nullable|required_without:google_id|required_if:provider,==,"facebook',
            'google_id' => 'string|nullable|required_without_all:password,facebook_id|required_if:provider,==,"google',
            ]);

        $credentials_login_facebook = $request->only('facebook_id', 'password');

        $credentials_login_mail = $request->only('email', 'password');
        $credentials_login_google = $request->only('google_id', 'password');

        if ($token = JWTAuth::attempt($credentials_login_mail)) {

            return response()->json(['message' => 'you are successfully logged in via mail'], 200);

        } elseif (($token = JWTAuth::attempt($credentials_login_facebook) &&
            $user = \DB::table('users')->where("facebook_id", $request->facebook_id)->first())) {
            return response()->json(['message' => 'you are successfully logged in via facebook'], 200);
        } elseif (($token = JWTAuth::attempt($credentials_login_google) &&
            $user = \DB::table('users')->where("google_id", $request->google_id)->first())) {
            return response()->json(['message' => 'you are successfully logged in via google'], 200);
        } else    return response()->json(['error' => 'check your credentials'], 400);

    }

    //return response()->json(compact('user', 'token'));


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'prenom' => 'string|max:255',
            'nom' => 'string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'string|required_without:provider',
            //'confirmpassword' => 'string|same:password|required_without:provider|nullable',
            'confirmpassword' => 'string|same:password|nullable',

            'provider' => 'in:facebook,google|required_without:password|nullable',
            'facebook_id' => 'string|nullable|required_without_all:password,google_id|required_if:provider,==,"facebook',
            'google_id' => 'string|nullable|required_without_all:password,facebook_id|required_if:provider,==,"google',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        } else {
            if (!$request->provider) {
                $user = User::create([
                    'firstname' => $request->get('prenom'),
                    'lastname' => $request->get('nom'),
                    'email' => $request->get('email'),
                    'password' => Hash::make($request->get('password'))]);
                $token = JWTAuth::fromUser($user);

                return response()->json([
                    'data' => $user,
                 //  'code' => 1,
                    'token' => $token,
                    'status'=> 201]);

            } else if ($request->provider = 'google') {

                $user = User::create([
                    'firstname' => $request->get('prenom'),
                    'lastname' => $request->get('nom'),
                    'email' => $request->get('email'),
                    'google_id' => $request->get('google_id'),
                    'facebook_id' => $request->get('facebook_id')]);
                $token = JWTAuth::fromUser($user);

                return response()->json([
                    'data' => $user,
                //    'code' => 2,
                    'token' => $token,
                    'status'=> 201]);

            } else {
                if ($request->provider = 'facebook') {
                    $user = User::create([
                        'firstname' => $request->get('prenom'),
                        'lastname' => $request->get('nom'),
                        'email' => $request->get('email'),
                        'google_id' => $request->get('google_id'),
                        'facebook_id' => $request->get('facebook_id')]);
                    $token = JWTAuth::fromUser($user);

                    return response()->json([
                        'data' => $user,
                    //    'code' => 3,
                        'token' => $token,
                        'status'=> 201]);

                }
            }



            //adding default roles
            $user
                ->roles()
                ->attach(Role::where('name', $request->get('role'))->first());
            /* $roles = Role::all();
             $user->roles()->attach(
                 $roles->toArray());
                 return $roles;*/
            //return response()->json(compact('user', 'token'), 201);
            return response()->json([
                'data' => $user,
                'token' => $token,
                'status'=> 201]);

        }
    }

    public function getAuthenticatedUser()
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        return response()->json(compact('user'));
    }

    public function getAllUsers()
    {
        return User::all()->toArray();
    }

    public function getFavoritePlans(Request $request)
    {
        $user = User::find($request->get('user_id'));
       return $user->favorite(Plan::class);
    }
}

//restart service mysql : sudo service mysql start
<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function StoreUser(StoreUserRequest $request)
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken($user->first_name)->plainTextToken;

        return response([
            'message' => 'Registration Successfully',
            'user' => $user,
            'token' => $token,
        ], );
    }

    public function login(LoginUserRequest $request)
    {
        $fields = $request->validated();

        // Gestion de l'option "se souvenir de moi"
        $remember = $fields['remember'] ?? false;

        unset($fields['remember']);

        $user = User::where('email', $fields['email'])->first();

        if(!$user || !Hash::check($fields['password'], $user->password))
        {
            return response([
                'message' => 'L\'Email ou le Mot de Passe est incorrect'
            ], 422);
        }

        // Définition de la durée de vie du token en fonction de l'option "se souvenir de moi"
        $tokenExpiration = $remember ? now()->addWeeks(2) : now()->addHours(2);

        $token = $user->createToken($user->last_name, ['*'], $tokenExpiration)->plainTextToken;

        return response([
            'message' => 'Login Successfully',
            'user' => $user,
            'token' => $token,
            'expires_at' => $tokenExpiration,
        ]);
    }

    public function logout(Request $request)
    {
        /**@var User $user */
        $request->user()->tokens()->delete();
        // $request->user()->currentAcessToken()->delete();

        return response([
            'message' => 'You Logged Out'
        ]);
    }
}

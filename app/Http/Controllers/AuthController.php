<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

// use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request): Response
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'username' => 'required|string|min:4|max:16|alpha_dash:ascii|unique:users,username',
            'password' => [
                'required',
                'string',
                // Password::min(8)->numbers()->symbols()->mixedCase(), // @TODO enforce when prod
            ],
        ]);

        /** @var User */
        $user = User::create([
            'username' => $fields['username'],
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => new UserResource($user),
            'token' => $token,
        ];

        return Response($response, 201);
    }

    /**
     * @todo add possibility to login by username?
     */
    public function login(Request $request): Response
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        /** @var User|null */
        $user = User::where('email', $fields['email'])->first();

        if (! $user || false === Hash::check($fields['password'], $user->getAuthPassword())) {
            return response([
                'message' => 'Bad creds',
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => new UserResource($user),
            'token' => $token,
        ];

        return Response($response, 201);
    }

    /**
     * @return string[]
     */
    public function logout(): array
    {
        /** @var User */
        $user = auth()->user();

        $user->tokens()->delete();

        return [
            'message' => 'Logged out',
        ];
    }
}
